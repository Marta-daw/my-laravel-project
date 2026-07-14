<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IdiomaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('idiomas')->delete();

        // Map de idiomas para insertar en la base de datos
        $idiomas = [
            ['nombre' => 'Català'],
            ['nombre' => 'Español'],
            ['nombre' => 'English'],
        ];

        DB::table('idiomas')->insert($idiomas);
    }
}
