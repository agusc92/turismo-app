<?php

namespace Database\Factories;

use App\Models\Complejo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplejoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Complejo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company,
            'direccion' => $this->faker->address,
            'mail' => $this->faker->unique()->safeEmail,
            'redesSociales' => $this->faker->url,
            'telefono' => $this->faker->phoneNumber,
            'servicio' => $this->faker->sentence(5),
            'adicional' => $this->faker->paragraph(1),
            'imagen' => $this->faker->imageUrl(),
        ];
    }
}
