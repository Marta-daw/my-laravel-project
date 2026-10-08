<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Requests\StoreLlibreRequest;

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
        //return Llibre::all();

        $llibres = Llibre::with('autors', 'categorias', 'idioma')->get();

        return response()->json($llibres);
    }

    public function autors($id)
    {
        $llibre = Llibre::findOrFail($id);

        return response()->json($llibre->autors);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLlibreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $validate = $request->validated();

            $autorIds = $validate['autor_id'];
            $categoriaIds = $validate['categoria_id'];

            unset($validate['autor_id'], $validate['categoria_id']);

            $llibre = Llibre::create($validate);

            $llibre->autors()->sync($autorIds);
            $llibre->categorias()->sync($categoriaIds);

            return response()->json(
                $llibre->load(['autors', 'categorias', 'idioma']),
                201
            );
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Llibre $llibre)
    {
        //Buscar llibre o llancem un error 404 si no existeix
        //$llibre = Llibre::findOrFail($id);

        // Retornar el llibre amb les seves relacions
        return response()->json($llibre->load(['autors', 'categorias', 'idioma']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLlibreRequest $request, Llibre $llibre)
    {
        return DB::transaction(function () use ($request) {
            $validate = $request->validated();

            $autorIds = $validate['autor_id'];
            $categoriaIds = $validate['categoria_id'];

            unset($validate['autor_id'], $validate['categoria_id']);

            $llibre = Llibre::create($validate);

            $llibre->autors()->sync($autorIds);
            $llibre->categorias()->sync($categoriaIds);

            return response()->json(
                $llibre->load(['autors', 'categorias', 'idioma']),
                201
            );
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Llibre $llibre)
    {
        $llibre->delete();

        return redirect()->back()->with('success', 'Llibre eliminat correctament');
    }
    
    public function contarLlibres()
    {
        $totalLlibres = Llibre::count();

        return response()->json(['total' => $totalLlibres]);
    }

    public function estadisticaLecturaAcabadaONo()
    {
        $llibresLlegits = Llibre::where('leido', 'Sí')->count();

        $llibresNoLlegits = Llibre::where('leido', 'No')->count();

        $llibresEnProceso = Llibre::where('leido', 'En proceso')->count();

        return response()->json([
            'llegits' => $llibresLlegits,
            'noLlegits' => $llibresNoLlegits,
            'enProceso' => $llibresEnProceso
        ]);
    }
}
