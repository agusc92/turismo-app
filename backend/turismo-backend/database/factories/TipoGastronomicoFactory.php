<?php

namespace Database\Factories;

use App\Models\TipoGastronomico;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoGastronomicoFactory extends Factory
{
    protected $model = TipoGastronomico::class;

    public function definition(): array
    {
        return [
            'tipo' => $this->faker->unique()->word . ' Gastronomico',
        ];
    }
}
