<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\DataKayu;
use App\Models\Pengguna;
use App\Models\Struk;
use App\Models\Transaksi;
use App\Models\User;
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

            DataKayu::factory(10)->create([
                'cabang_id' => $cabang->id,
            ]);

            $pengguna->each(function ($user) use ($cabang) {

                $transaksi = Transaksi::factory(3)->create([
                    'user_id' => $user->id,
                    'cabang_id' => $cabang->id,
                ]);

                $transaksi->each(function ($trx) {
                    Struk::factory()->create([
                        'transaksi_id' => $trx->id,
                    ]);
                });
            });
        });
    }
}
