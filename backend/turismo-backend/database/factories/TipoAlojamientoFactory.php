<?php

namespace Database\Factories;

use App\Models\TipoAlojamiento;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoAlojamientoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TipoAlojamiento::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipo' => $this->faker->unique()->word() . '-' . $this->faker->unique()->randomNumber(5), // Generar un tipo más único
        ];
    }
}
