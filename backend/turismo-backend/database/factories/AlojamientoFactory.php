<?php

namespace Database\Factories;

use App\Models\Alojamiento;
use App\Models\TipoAlojamiento;
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
            'imagen' => $this->faker->imageUrl(),
            'latitud' => $this->faker->latitude(-38.7, -38.5),
            'longitud' => $this->faker->longitude(-58.9, -58.6),
            'habilitado' => $this->faker->boolean(),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Alojamiento $alojamiento) {
            // Por defecto, no adjuntamos tipos de alojamiento.
            // Los tests o seeders pueden usar el estado `withTiposAlojamiento` si lo necesitan.
        });
    }

    /**
     * Indicate that the alojamiento should have tipos de alojamiento.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withTiposAlojamiento()
    {
        return $this->afterCreating(function (Alojamiento $alojamiento) {
            $tiposAlojamiento = TipoAlojamiento::inRandomOrder()->limit(rand(1, 3))->get();
            if ($tiposAlojamiento->isEmpty()) {
                $defaultTypes = ['Hotel', 'Cabaña', 'Hostel', 'Apart Hotel'];
                foreach ($defaultTypes as $defaultType) {
                    TipoAlojamiento::firstOrCreate(['tipo' => $defaultType]);
                }
                $tiposAlojamiento = TipoAlojamiento::inRandomOrder()->limit(rand(1, 3))->get();
            }
            $alojamiento->tiposAlojamiento()->attach($tiposAlojamiento->pluck('id'));
        });
    }

    /**
     * Indicate that the alojamiento should not have tipos de alojamiento.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withoutTiposAlojamiento()
    {
        return $this->afterCreating(function (Alojamiento $alojamiento) {
        });
    }
}
