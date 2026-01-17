<?php

namespace Database\Factories;

use App\Models\Cabang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cabang>
 */
class CabangFactory extends Factory
{
    protected $model = Cabang::class;

    public function definition()
    {
        return [
            'nama_cabang' => 'Cabang ' . $this->faker->city,
            'alamat' => $this->faker->address,
        ];
    }
}
