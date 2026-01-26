<?php

namespace App\Http\Controllers;

use App\Models\DataKayu;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class KayuController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $kayu = DataKayu::with('cabang')
            ->when(
                $user->role === 'admin',
                fn($q) =>
                $q->where('cabang_id', $user->cabang_id)
            )
            ->when($request->search, function ($q) use ($request) {
                $q->where('jenis_kayu', 'like', '%' . $request->search . '%');
            })
            ->when($request->cabang_id, function ($q) use ($request) {
                $q->where('cabang_id', $request->cabang_id);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $cabang = Cabang::orderBy('nama_cabang')->get();

        return view('pages.kayu', compact('kayu', 'cabang'));
    }



    public function print(Request $request)
    {
        $user = Auth::user();
        $kayu = DataKayu::with('cabang')
            ->when(
                $user->role === 'admin',
                fn($q) =>
                $q->where('cabang_id', $user->cabang_id)
            )
            ->when(
                $request->search,
                fn($q) =>
                $q->where('jenis_kayu', 'like', '%' . $request->search . '%')
            )
            ->get();

        return Pdf::loadView('pages.kayu-print', compact('kayu'))
            ->setPaper('A4', 'portrait')
            ->stream('data-kayu.pdf');
    }



    public function store(Request $request)
    {
        try {
            $request->validate([
                'cabang_id'    => 'required|exists:cabang,id',
                'jenis_kayu'   => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('data_kayu', 'jenis_kayu')
                        ->where(fn($q) => $q->where('cabang_id', $request->cabang_id)),
                ],
                'jumlah'       => 'required|integer|min:1',
                'harga_satuan' => 'required|numeric|min:0',
            ], [
                'jenis_kayu.unique' => 'Data kayu sudah tersedia di cabang ini',
                'jumlah.min'        => 'Jumlah minimal stok tidak sesuai',
                'harga_satuan.min'  => 'Harga satuan tidak sesuai',
            ]);


            DataKayu::create($request->all());

            notify()
                ->success(
                    'Berhasil Menambahkan Data ' . $request->jenis_kayu,
                    'Tambah Data Kayu Berhasil'
                );
        } catch (Exception $e) {
            Log::error('Gagal Menambahkan Data Kayu', [
                'error' => $e->getMessage()
            ]);

            notify()->error(
                'Gagal Menambahkan Data Kayu '  . $request->jenis_kayu,
                'Terjadi Kesalahan ' . $e->getMessage()
            );
        }
        return redirect()->route('kayu');
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'cabang_id'    => 'required|exists:cabang,id',
                'jenis_kayu'   => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('data_kayu', 'jenis_kayu')
                        ->where(fn($q) => $q->where('cabang_id', $request->cabang_id))
                        ->ignore($id),
                ],
                'jumlah'       => 'required|integer|min:1',
                'harga_satuan' => 'required|numeric|min:0',
            ], [
                'jenis_kayu.unique' => 'Data kayu sudah tersedia di cabang ini',
                'jumlah.min'        => 'Jumlah minimal stok tidak sesuai',
                'harga_satuan.min'  => 'Harga satuan tidak sesuai',
            ]);

            DataKayu::findOrFail($id)->update($request->all());

            notify()
                ->success(
                    'Berhasil Memperbarui Data ' . $request->jenis_kayu,
                    'Edit Data Kayu Berhasil'
                );
        } catch (Exception $e) {
            Log::error('Gagal Memperbarui Data Kayu', [
                'error' => $e->getMessage()
            ]);

            notify()->error(
                'Gagal Memperbarui Data Kayu '  . $request->jenis_kayu,
                'Terjadi Kesalahan ' . $e->getMessage()
            );
        }
        return redirect()->route('kayu');
    }

    public function destroy($id)
    {
        try {
            $data = DataKayu::findOrFail($id);
            $data->delete();
            notify()
                ->success(
                    'Berhasil Menghapus Data ' . $data->jenis_kayu,
                    'Hapus Data Kayu Berhasil'
                );
        } catch (Exception $e) {
            Log::error('Gagal Menghapus Data Kayu', [
                'error' => $e->getMessage()
            ]);

            notify()->error(
                'Gagal Menghapus Data Kayu '  . $data->jenis_kayu,
                'Terjadi Kesalahan ' . $e->getMessage()
            );
        }

        return redirect()->route('kayu');
    }
}
