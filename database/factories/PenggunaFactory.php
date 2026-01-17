<?php

namespace Database\Factories;

use App\Models\Cabang;
use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;

    public function definition()
    {
        return [
            'cabang_id' => Cabang::factory(),
            'nama' => $this->faker->name,
            'username' => $this->faker->unique()->userName,
            'password' => bcrypt('password'),
            'role' => $this->faker->randomElement(['superadmin', 'admin']),
        ];
    }
}
