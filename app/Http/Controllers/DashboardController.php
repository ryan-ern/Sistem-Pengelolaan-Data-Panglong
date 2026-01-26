<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Transaksi;
use App\Models\DataKayu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $tahun = $request->get('tahun', now()->year);

        if ($user->role != 'admin') {
            $cabangId = $request->get('cabang_id');
        } else {
            $cabangId = $user->cabang_id;
        }


        $transaksiKeluar = Transaksi::query()
            ->where('jenis_transaksi', 'keluar')
            ->whereYear('tanggal', $tahun)
            ->when($cabangId, fn($q) => $q->where('cabang_id', $cabangId))
            ->get();


        $rekapKayu = [];

        foreach ($transaksiKeluar as $trx) {
            preg_match_all('/\d+\.\s(.+?)\s\((\d+)\s-\sRp\.\s([\d\.]+)\)/', $trx->informasi, $matches, PREG_SET_ORDER);

            foreach ($matches as $m) {
                $nama = $m[1];
                $qty  = (int) $m[2];

                $rekapKayu[$nama] = ($rekapKayu[$nama] ?? 0) + $qty;
            }
        }

        arsort($rekapKayu);

        $penjualanTerbanyak = collect($rekapKayu)->map(function ($qty, $nama) {
            return (object)[
                'jenis_kayu' => $nama,
                'qty' => $qty
            ];
        })->first();



        $rataBulanan = $transaksiKeluar->sum('total') / 12;

        $transaksi = DB::table('transaksi')
            ->when($cabangId, fn($q) => $q->where('cabang_id', $cabangId))
            ->whereYear('tanggal', $tahun);

        $trenPenjualan = $transaksi
            ->clone() // penting agar query tidak saling ganggu
            ->where('jenis_transaksi', 'keluar')
            ->selectRaw('MONTH(tanggal) as bulan, SUM(total) as total')
            ->groupByRaw('MONTH(tanggal)')
            ->pluck('total', 'bulan');


        $stokKayu = DataKayu::query()
            ->when($cabangId, fn($q) => $q->where('cabang_id', $cabangId))
            ->get(['jenis_kayu', 'jumlah']);



        $cabang = Cabang::get();

        return view('pages.dashboard', compact(
            'tahun',
            'penjualanTerbanyak',
            'rataBulanan',
            'trenPenjualan',
            'stokKayu',
            'cabang'
        ));
    }
}
