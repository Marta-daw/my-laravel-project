<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('autors')->delete();

        // Map de autores para insertar en la base de datos
        $autors = [
            ['nombre' => 'Sandra',    'apellido_1' => 'Sabatés',   'apellido_2' => null],
            ['nombre' => 'Judit',     'apellido_1' => 'Crehuet',   'apellido_2' => null],
            ['nombre' => 'Leticia',   'apellido_1' => 'Dolera',    'apellido_2' => null],
            ['nombre' => 'Raquel',    'apellido_1' => 'Riba',      'apellido_2' => 'Rossy'],
            ['nombre' => 'Carmen',    'apellido_1' => 'Laforet',   'apellido_2' => null],
            ['nombre' => 'Claudio',   'apellido_1' => 'Stassi',    'apellido_2' => null],
            // Llista 1
            ['nombre' => 'Julie',     'apellido_1' => 'Maroh',     'apellido_2' => null],
            ['nombre' => 'Marina',    'apellido_1' => 'Velasco',   'apellido_2' => null],
            ['nombre' => 'Ana',       'apellido_1' => 'Oncina',    'apellido_2' => null],
            ['nombre' => 'Paloma',    'apellido_1' => 'Alma',      'apellido_2' => null],
            ['nombre' => 'R.J.',      'apellido_1' => 'Palacio',   'apellido_2' => null],
            ['nombre' => 'Alfonso',   'apellido_1' => 'Casas',     'apellido_2' => null],
            ['nombre' => 'Guillem',   'apellido_1' => 'Clua',      'apellido_2' => null],
            ['nombre' => 'Mónica',    'apellido_1' => 'Gutiérrez', 'apellido_2' => null],
            ['nombre' => 'Shea',      'apellido_1' => 'Ernshaw',   'apellido_2' => null],
            ['nombre' => 'Eiko',      'apellido_1' => 'Kadono',    'apellido_2' => null],
            ['nombre' => 'Daniella',  'apellido_1' => 'Martí',     'apellido_2' => null],
            // Llista 2
            ['nombre' => 'Carlos',    'apellido_1' => 'Ruiz',      'apellido_2' => 'Zafón'],
            ['nombre' => 'William',   'apellido_1' => 'Shakespeare', 'apellido_2' => null],
            ['nombre' => 'Mercè',     'apellido_1' => 'Rodoreda',  'apellido_2' => null],
            ['nombre' => 'Elsa',      'apellido_1' => 'Punset',    'apellido_2' => null],
            ['nombre' => 'Taylor',    'apellido_1' => 'Jenkins',   'apellido_2' => 'Reid'],
            ['nombre' => 'Serena',    'apellido_1' => 'Valentino', 'apellido_2' => null],
            ['nombre' => 'Joël',      'apellido_1' => 'Dicker',    'apellido_2' => null],
            ['nombre' => 'Guillermo', 'apellido_1' => 'Del Toro',     'apellido_2' => null],
            ['nombre' => 'Chuck', 'apellido_1' => 'Hogan',     'apellido_2' => null],
            ['nombre' => 'Michael',   'apellido_1' => 'Ende',      'apellido_2' => null],
            ['nombre' => 'Stanisław', 'apellido_1' => 'Lem',       'apellido_2' => null],
            ['nombre' => 'Diana',     'apellido_1' => 'Wynne',     'apellido_2' => 'Jones'],
        ];

        // Insertar los autores en la base de datos
        foreach ($autors as $autor) {
            DB::table('autors')->insert($autor);
        }
    }
}
