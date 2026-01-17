<?php

namespace Database\Factories;

use App\Models\Cabang;
use App\Models\Pengguna;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    public function definition()
    {
        return [
            'user_id' => Pengguna::factory(),
            'cabang_id' => Cabang::factory(),
            'tanggal' => now(),
            'jenis_transaksi' => $this->faker->randomElement(['masuk', 'keluar']),
            'informasi' => $this->faker->sentence,
            'total' => $this->faker->numberBetween(100000, 5000000),
        ];
    }
}
