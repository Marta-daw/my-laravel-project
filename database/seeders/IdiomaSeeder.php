<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            ['idioma' => 'Català'],
            ['idioma' => 'Español'],
            ['idioma' => 'English'],
        ];

        DB::table('idiomas')->insert($idiomas);
    }
}
