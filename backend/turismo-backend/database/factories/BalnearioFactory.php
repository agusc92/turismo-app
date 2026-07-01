<?php

namespace Database\Factories;

use App\Models\Balneario;
use Illuminate\Database\Eloquent\Factories\Factory;

class BalnearioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Balneario::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company . ' Balneario',
            'direccion' => $this->faker->address,
            'telefono' => $this->faker->phoneNumber,
            'redesSociales' => $this->faker->url,
            'servicios' => $this->faker->sentence(5),
            'mail' => $this->faker->unique()->safeEmail,
            'accesibilidad' => $this->faker->sentence(3),
            'fecha_desde_hasta' => 'Diciembre a Marzo',
            'imagen' => $this->faker->imageUrl(),
            'latitud' => $this->faker->latitude(-38.7, -38.5),
            'longitud' => $this->faker->longitude(-58.9, -58.6),
            'habilitado' => $this->faker->boolean(),
        ];
    }
}
