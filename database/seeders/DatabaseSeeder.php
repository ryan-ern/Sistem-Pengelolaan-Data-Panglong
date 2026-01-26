<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\DataKayu;
use App\Models\Pengguna;
use App\Models\Struk;
use App\Models\Transaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1️⃣ Buat cabang
        $cabangList = Cabang::factory(3)->create();

        // 2️⃣ BUAT SUPERADMIN & ADMIN (SATU KALI)
        Pengguna::factory()->create([
            'cabang_id' => $cabangList->first()->id,
            'nama' => 'superadmin',
            'username' => 'super',
            'password' => bcrypt('super'),
            'role' => 'superadmin',
        ]);

        Pengguna::factory()->create([
            'cabang_id' => $cabangList->first()->id,
            'nama' => 'admin',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        // 3️⃣ LOOP CABANG
        $cabangList->each(function ($cabang) {

            // pengguna random (tanpa username tetap)
            $pengguna = Pengguna::factory(5)->create([
                'cabang_id' => $cabang->id,
            ]);

            DataKayu::factory(15)->create([
                'cabang_id' => $cabang->id,
            ]);


            $pengguna->each(function ($user) use ($cabang) {

                Transaksi::factory(3)->make([
                    'user_id'   => $user->id,
                    'cabang_id' => $cabang->id,
                ])->each(function ($trx) use ($user, $cabang) {

                    // ambil 1–4 kayu random
                    $kayuItems = DataKayu::where('cabang_id', $cabang->id)
                        ->inRandomOrder()
                        ->take(rand(1, 4))
                        ->get();

                    $info  = '';
                    $total = 0;
                    $no    = 1;

                    foreach ($kayuItems as $kayu) {
                        $qty      = rand(1, 5);
                        $subtotal = $qty * $kayu->harga_satuan;
                        $total   += $subtotal;

                        $info .= $no . '. ' . $kayu->jenis_kayu .
                            ' (' . $qty . ' - Rp. ' .
                            number_format($subtotal, 0, ',', '.') . ")\n";

                        $no++;
                    }

                    // simpan transaksi
                    $trx = Transaksi::create([
                        'user_id'         => $user->id,
                        'cabang_id'       => $cabang->id,
                        'jenis_transaksi' => collect(['masuk', 'keluar'])->random(),
                        'tanggal'         => now()->subDays(rand(0, 30)),
                        'informasi'       => trim($info),
                        'total'           => $total,
                    ]);

                    // buat struk
                    Struk::factory()->create([
                        'transaksi_id' => $trx->id,
                        'status_struk' => 'dicetak',
                        'tanggal_cetak' => now(),
                    ]);
                });
            });
        });
    }
}
