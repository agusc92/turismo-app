<?php

namespace Database\Factories;

use App\Models\Actividad;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tipo;

class ActividadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Actividad::class;

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
            'redes_sociales' => $this->faker->url,
            'web' => $this->faker->url,
            'mail' => $this->faker->unique()->safeEmail,
            'telefono' => $this->faker->phoneNumber,
            'imagen' => $this->faker->imageUrl(),
            'tipo_id' => Tipo::factory(),
            'dias_y_horarios' => $this->faker->sentence(4),
            'latitud' => $this->faker->latitude(-38.7, -38.5),
            'longitud' => $this->faker->longitude(-58.9, -58.6),
        ];
    }
}
