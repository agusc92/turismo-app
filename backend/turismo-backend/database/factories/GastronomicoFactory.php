<?php

namespace Database\Factories;

use App\Models\Gastronomico;
use Illuminate\Database\Eloquent\Factories\Factory;

class GastronomicoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Gastronomico::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company . ' Restaurant',
            'direccion' => $this->faker->address,
            'telefono' => $this->faker->phoneNumber,
            'redesSociales' => $this->faker->url,
            'tiendaOnline' => $this->faker->url,
            'extras' => $this->faker->sentence(5),
            'horario' => 'L-D 09:00-23:00',
            'imagen' => $this->faker->imageUrl(),
            'latitud' => $this->faker->latitude(-38.7, -38.5),
            'longitud' => $this->faker->longitude(-58.9, -58.6),
        ];
    }
}
