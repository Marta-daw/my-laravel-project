<?php

namespace Database\Factories;

use App\Models\Idioma;
use App\Models\Llibre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Llibre>
 */
class LlibreFactory extends Factory
{
    protected $model = Llibre::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(4),
            'paginas' => fake()->numberBetween(1, 800),
            'sinopsis' => fake()->paragraph(),
            'idioma_id' => Idioma::factory(),
            'leido' => fake()->randomElement(['si', 'no', 'en_proceso']),
            'puntuacion' => fake()->optional()->numberBetween(0, 5),
            'portada' => fake()->optional()->url(),
        ];
    }
}