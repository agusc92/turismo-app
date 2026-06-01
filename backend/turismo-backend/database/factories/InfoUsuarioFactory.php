<?php

namespace Database\Factories;

use App\Models\InfoUsuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Tipo;

class InfoUsuarioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InfoUsuario::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ciudad' => $this->faker->city,
            'edad' => $this->faker->numberBetween(18, 90),
            'estadia' => $this->faker->randomElement(['1 día', '3 días', '1 semana', '2 semanas']),
            'integrantes' => $this->faker->numberBetween(1, 5),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (InfoUsuario $infoUsuario) {
            if (Tipo::count() === 0) {
                Tipo::factory()->create();
            }
            $intereses = Tipo::inRandomOrder()->limit($this->faker->numberBetween(0, 3))->pluck('id');
            $infoUsuario->intereses()->attach($intereses);
        });
    }
}
