<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Llibre;
use App\Models\Idioma;
use App\Models\Autor;
use App\Models\Categoria;

class LlibreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Llibre::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'titulo' => 'required|string|max:255',
            'páginas' => 'required|integer|min:1',
            'sinopsis' => 'nullable|string',
            'idioma_id' => 'required|exists:idiomas,id',
            'leido' => 'required|in:Sí,No,En progreso',
            'puntuación' => 'nullable|integer|between:0,5',
            'portada' => 'nullable|string',
            'autor_id' => 'required|exists:autors,id',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        Llibre::create($validate);

        return redirect()->back()->with('success', 'Llibre creat correctament');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Buscar llibre o llancem un error 404 si no existeix
        $llibre = llibre::findOrFail($id);

        $idiomaIds = $llibre->idioma->pluck('id');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Llibre $llibre)
    {
        $validate = $request->validate([
            'titulo' => 'required|string|max:255',
            'páginas' => 'required|integer|min:1',
            'sinopsis' => 'nullable|string',
            'idioma_id' => 'required|exists:idiomas,id',
            'leido' => 'required|in:Sí,No,En progreso',
            'puntuación' => 'nullable|integer|between:0,5',
            'portada' => 'nullable|string',
            'autor_id' => 'required|exists:autors,id',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $llibre = Llibre::findOrFail($id);
        $llibre->update($validate);

        return redirect()->back()->with('success', 'Llibre actualitzat correctament');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Llibre $llibre)
    {
        $llibre->delete();

        return redirect()->back()->with('success', 'Llibre eliminat correctament');
    }
    
}
