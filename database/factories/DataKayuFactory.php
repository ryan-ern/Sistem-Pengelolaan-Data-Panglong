<?php

namespace Database\Factories;

use App\Models\Cabang;
use App\Models\DataKayu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataKayu>
 */
class DataKayuFactory extends Factory
{
    protected $model = DataKayu::class;

    public function definition()
    {
        return [
            'cabang_id' => Cabang::factory(),
            'jenis_kayu' => $this->faker->randomElement(['Jati', 'Mahoni', 'Meranti']),
            'jumlah' => $this->faker->numberBetween(1, 500),
            'harga_satuan' => $this->faker->numberBetween(50000, 500000),
        ];
    }
}
