<?php

namespace Database\Factories;

use App\Models\Idioma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Idioma>
 */
class IdiomaFactory extends Factory
{
    protected $model = Idioma::class;

    public function definition(): array
    {
        return [
            'idioma' => fake()->unique()->languageCode(),
        ];
    }
}