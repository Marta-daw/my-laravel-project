<?php

namespace Database\Factories;

use App\Models\Autor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Autor>
 */
class AutorFactory extends Factory
{
    protected $model = Autor::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellido_1' => fake()->lastName(),
            'apellido_2' => fake()->optional()->lastName(),
        ];
    }
}