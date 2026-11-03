<?php

namespace Tests\Feature;

use App\Models\Autor;
use App\Models\Categoria;
use App\Models\Llibre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LlibreApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_retorna_un_llibre_amb_les_seves_relacions(): void
    {
        $autor = Autor::factory()->create();
        $categoria = Categoria::factory()->create();

        $llibre = Llibre::factory()->create();

        $llibre->autores()->attach($autor);
        $llibre->categorias()->attach($categoria);

        $response = $this->getJson("/api/llibres/{$llibre->id}");

        $response
            ->assertOk()
            ->assertJsonPath('id', $llibre->id)
            ->assertJsonPath('autores.0.id', $autor->id)
            ->assertJsonPath('categorias.0.id', $categoria->id);
    }

    public function test_no_pot_crear_un_llibre_sense_titol(): void
    {
        $this->postJson('/api/llibres', [
            'paginas' => 100,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['titulo']);
    }

    public function test_pot_crear_un_llibre(): void
    {
        $idioma = Idioma::factory()->create();
        $autor = Autor::factory()->create();

        $this->postJson('/api/llibres', [
            'titulo' => 'Títol de prova',
            'paginas' => 100,
            'idioma_id' => $idioma->id,
            'leido' => 'no',
            'autor_id' => [$autor->id],
            'categoria_id' => [],
        ])
            ->assertCreated()
            ->assertJsonPath('titulo', 'Títol de prova');
    }
}