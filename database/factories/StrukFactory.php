<?php

namespace Database\Factories;

use App\Models\Struk;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Struk>
 */
class StrukFactory extends Factory
{
    protected $model = Struk::class;

    public function definition()
    {
        return [
            'transaksi_id' => Transaksi::factory(),
            'tanggal_cetak' => now(),
            'status_struk' => $this->faker->randomElement(['dicetak', 'belum_dicetak']),
        ];
    }
}
