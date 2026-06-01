<?php

namespace Database\Factories;

use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Evento::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->sentence(3),
            'direccion' => $this->faker->address,
            'descripcion' => $this->faker->paragraph(2),
            'fecha' => $this->faker->dateTimeBetween('+1 week', '+1 year')->format('Y-m-d H:i:s'),
            'lugar' => $this->faker->city,
            'imagen' => $this->faker->imageUrl(),
            'destacado' => $this->faker->boolean(),
        ];
    }
}
