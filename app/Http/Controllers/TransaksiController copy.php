<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\DataKayu;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        /** ================= CABANG AKTIF ================= */
        $activeCabangId = null;

        if ($user->role === 'admin') {
            // admin (bukan super) → otomatis cabangnya sendiri
            $activeCabangId = $user->cabang_id;
        } else {
            // selain admin → dari request
            $activeCabangId = $request->cabang_id;
        }

        /** ================= TRANSAKSI ================= */
        $transaksi = Transaksi::with(
            $user->role === 'admin'
                ? ['struk']
                : ['cabang', 'struk']
        )
            ->when(
                $user->role === 'admin',
                fn($q) =>
                $q->where('user_id', $user->id)
            )
            ->when(
                $request->search,
                fn($q) =>
                $q->where('informasi', 'like', '%' . $request->search . '%')
            )
            ->when(
                $activeCabangId,
                fn($q) =>
                $q->where('cabang_id', $activeCabangId)
            )
            ->when(
                $request->jenis_transaksi,
                fn($q) =>
                $q->where('jenis_transaksi', $request->jenis_transaksi)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /** ================= CABANG LIST ================= */
        $cabang = Cabang::orderBy('nama_cabang')->get();

        /** ================= DATA KAYU ================= */
        $kayu = collect(); // default kosong

        if ($activeCabangId) {
            $kayu = DataKayu::where('cabang_id', $activeCabangId)
                ->orderBy('jumlah')
                ->get();
        }

        return view('pages.transaksi', compact(
            'transaksi',
            'cabang',
            'kayu',
            'activeCabangId'
        ));
    }


    public function printFilter(Request $request)
    {
        $user = Auth::user();

        $transaksi = Transaksi::with(['cabang'])
            ->when(
                $request->cabang_id,
                fn($q) => $q->where('cabang_id', $request->cabang_id)
            )
            ->when(
                $user->role === 'admin',
                fn($q) =>
                $q->where('user_id', $user->id)
            )
            ->when(
                $request->jenis_transaksi,
                fn($q) => $q->where('jenis_transaksi', $request->jenis_transaksi)
            )
            ->when(
                $request->search,
                fn($q) => $q->where('informasi', 'like', '%' . $request->search . '%')
            )
            ->when($request->periode, function ($q) use ($request) {
                match ($request->periode) {
                    'hari'   => $q->whereDate('tanggal', now()),
                    'minggu' => $q->whereBetween('tanggal', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]),
                    'bulan'  => $q->whereMonth('tanggal', now()->month)
                        ->whereYear('tanggal', now()->year),
                    'tahun'  => $q->whereYear('tanggal', now()->year),
                    default  => null, // all data
                };
            })
            ->latest()
            ->get();

        $judul = match ($request->periode) {
            'hari'   => 'Harian',
            'minggu' => 'Mingguan',
            'bulan'  => 'Bulanan',
            'tahun'  => 'Tahunan',
            default  => 'Semua Data',
        };

        $pdf = PDF::loadView(
            'pages.transaksi-print',
            compact('transaksi', 'judul')
        )->setPaper('A4', 'landscape');

        return $pdf->stream(
            'laporan-transaksi-' . strtolower(str_replace(' ', '-', $judul)) . '-' . now()->format('d-m-Y') . '.pdf'
        );
    }

    public function printById($id)
    {
        $user = Auth::user();

        $transaksi = Transaksi::with(['cabang', 'pengguna'])->when(
            $user->role === 'admin',
            fn($q) =>
            $q->where('user_id', $user->id)
        )->findOrFail($id);

        $transaksi->struk()->updateOrCreate(
            ['transaksi_id' => $transaksi->id],
            [
                'tanggal_cetak' => now(),
                'status_struk'  => 'dicetak',
            ]
        );
        // 80mm x auto height
        $customPaper = [0, 0, 226, 600];
        $pdf = PDF::loadView(
            'pages.transaksi-struk',
            compact('transaksi')
        )->setPaper($customPaper, 'portrait');

        return $pdf->stream(
            'struk-transaksi-' . $transaksi->id . '.pdf'
        );
    }

    private function updateStokKayu(string $informasi, string $jenisTransaksi, int $cabangId): void
    {
        /**
         * Contoh baris:
         * 1. Jati (2 - Rp. 401.574)
         */
        $lines = explode("\n", trim($informasi));

        foreach ($lines as $line) {

            if (!preg_match('/\d+\.\s(.+?)\s\((\d+)\s-\sRp\./', $line, $matches)) {
                continue;
            }

            $namaKayu = trim($matches[1]);
            $qty      = (int) $matches[2];

            $kayu = DataKayu::where('cabang_id', $cabangId)
                ->where('jenis_kayu', $namaKayu)
                ->lockForUpdate()
                ->first();

            if (!$kayu) {
                throw new \Exception("Kayu {$namaKayu} tidak ditemukan di cabang ini");
            }

            if ($jenisTransaksi === 'keluar') {
                if ($kayu->jumlah < $qty) {
                    throw new \Exception("Stok {$namaKayu} tidak mencukupi");
                }

                $kayu->decrement('jumlah', $qty);
            } else {
                $kayu->increment('jumlah', $qty);
            }
        }
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validate([
                'cabang_id'       => 'nullable',
                'jenis_transaksi' => 'required|in:masuk,keluar',
                'tanggal'         => 'required|date',
                'informasi'       => 'required|string',
                'total'           => 'required|numeric|min:0',
            ]);

            $data['user_id'] = Auth::id();

            $data['cabang_id'] = Auth::user()->role === 'admin'
                ? Auth::user()->cabang_id
                : $data['cabang_id'];

            $transaksi = Transaksi::create($data);

            $this->updateStokKayu(
                $data['informasi'],
                $data['jenis_transaksi'],
                $data['cabang_id']
            );

            $transaksi->struk()->firstOrCreate(
                ['transaksi_id' => $transaksi->id],
                [
                    'tanggal_cetak' => now(),
                    'status_struk'  => 'belum_dicetak'
                ]
            );

            DB::commit();

            notify()->success(
                'Transaksi Baru berhasil disimpan & stok diperbarui',
                'Transaksi Berhasil'
            );

            return redirect()
                ->route('transaksi')
                ->with('printById', $transaksi->id);
        } catch (\Exception $e) {
            DB::rollBack();

            notify()->error(
                'Gagal menyimpan transaksi',
                $e->getMessage()
            );

            return redirect()->route('transaksi');
        }
    }



    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {

            $transaksi = Transaksi::findOrFail($id);

            // rollback stok lama
            $this->updateStokKayu(
                $transaksi->informasi,
                $transaksi->jenis_transaksi === 'masuk' ? 'keluar' : 'masuk',
                $transaksi->cabang_id
            );

            $data = $request->validate([
                'jenis_transaksi' => 'required|in:masuk,keluar',
                'tanggal' => 'required',
                'informasi' => 'required',
                'total' => 'required|numeric'
            ]);

            $transaksi->update($data);

            // apply stok baru
            $this->updateStokKayu(
                $data['informasi'],
                $data['jenis_transaksi'],
                $transaksi->cabang_id
            );
        });

        notify()->success('Transaksi diperbarui');
        return back();
    }


    public function destroy($id)
    {
        try {
            $data = Transaksi::findOrFail($id);
            $data->delete();

            notify()->success(
                'Berhasil menghapus transaksi ' . $data->informasi,
                'Hapus Data transaksi Berhasil'
            );
        } catch (Exception $e) {
            Log::error('Gagal hapus transaksi', [
                '
            error' => $e->getMessage()
            ]);

            notify()->error(
                'Gagal menghapus data transaksi ' . $data->informasi,
                'Terjadi Kesalahan ' . $e->getMessage()
            );
        }

        return redirect()->route('transaksi');
    }
}
