<?php

namespace Database\Factories;

use App\Models\Alojamiento;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlojamientoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Alojamiento::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company . ' Alojamiento',
            'direccion' => $this->faker->address,
            'telefono' => $this->faker->phoneNumber,
            'redesSociales' => $this->faker->url,
            'paginaWeb' => $this->faker->url,
            'mail' => $this->faker->unique()->safeEmail,
            'mascotas' => $this->faker->boolean(),
            'periodoApertura' => $this->faker->randomElement(['Todo el año', 'Temporada alta', 'Verano']),
            'tipo' => $this->faker->randomElement(['Hotel', 'Cabaña', 'Hostel', 'Apart Hotel']),
            'imagen' => $this->faker->imageUrl(),
            'latitud' => $this->faker->latitude(-38.7, -38.5),
            'longitud' => $this->faker->longitude(-58.9, -58.6),
            'habilitado' => $this->faker->boolean(),
        ];
    }
}
