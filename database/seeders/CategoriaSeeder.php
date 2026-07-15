<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorias')->delete();

        // Map de categorias para insertar en la base de datos
        $categorias = [
            ['nombre' => 'Art'],
            ['nombre' => 'Assaig General'],
            ['nombre' => 'Biografia'],
            ['nombre' => 'Ciència'],
            ['nombre' => 'Tecnologia'],
            ['nombre' => 'Ciència-ficció'],
            ['nombre' => 'Cinema'],
            ['nombre' => 'Còmic'],
            ['nombre' => 'Cuina'],
            ['nombre' => 'Disseny'],
            ['nombre' => 'Estil de vida'],
            ['nombre' => 'Fantasia'],
            ['nombre' => 'Filosofia'],
            ['nombre' => 'Història'],
            ['nombre' => 'Infantil i Juvenil'],
            ['nombre' => 'LGTBIQ+'],
            ['nombre' => 'Manga'],
            ['nombre' => 'Memòries'],
            ['nombre' => 'Música'],
            ['nombre' => 'Natura'],
            ['nombre' => 'Narrativa General'],
            ['nombre' => 'No ficció'],
            ['nombre' => 'Novel·la Clàssica'],
            ['nombre' => 'Novel·la Gràfica'],
            ['nombre' => 'Novel·la Històrica'],
            ['nombre' => 'Novel·la Negra'],
            ['nombre' => 'Novel·la Policíaca'],
            ['nombre' => 'Novel·la Romàntica'],
            ['nombre' => 'Novel·la Sentimental'],
            ['nombre' => 'Poesia'],
            ['nombre' => 'Política'],
            ['nombre' => 'Psicologia'],
            ['nombre' => 'Salut i Benestar'],
            ['nombre' => 'Societat'],
            ['nombre' => 'Teatre'],
            ['nombre' => 'Thriller'],
            ['nombre' => 'Viatges'],
        ];

        DB::table('categorias')->insert($categorias);
    }
}
