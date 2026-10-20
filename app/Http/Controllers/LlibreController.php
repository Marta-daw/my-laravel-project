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
    public function index(Request $request)
    {
        //return Llibre::all();
        // $llibres = Llibre::with('autores', 'categorias', 'idioma')->get();
        // return response()->json($llibres);

        // Definir los campos a usar para ordenar.
        $allowedSorts = ['titulo', 'created_at'];

        // Comprobar que el valor de 'sort' es uno de los permitidos por defecto.
        $sort = in_array($request->string('sort')->toString(), $allowedSorts, true)
            ? $request->string('sort')->toString()
            : 'titulo';

        // Limitar el número de resultados por página a un máximo de 50
        $perPage = min(
            max($request->integer('per_page', 10), 1),
            50
        );
        
        // Construir consulta de los libros, donde el with() carga las relaciones indicadas,
        // when() aplica el filtro solo si se cumplen unas condiciones, en este caso si se ha 
        // enviado el parámetro 'search', con orderBy() ordenamos los resulktados por el campo 
        // indicado y con paginate() limitamos el número de resultados por página.
        $llibres = Llibre::with('autores', 'categorias', 'idioma')
            ->when($request->filled('search'), fn ($query) => $query->where('titulo', 'like', '%' . $request->string('search')->toString() . '%'))
            ->orderBy($sort)
            ->paginate($perPage);

        return response()->json($llibres);
    }

    /**
     * Función para obtener los autores de un libro específico
     * @param int $id El ID del libro
     * @return \Illuminate\Http\JsonResponse La respuesta JSON con los autores del libro
     */

    public function autores($id)
    {
        $llibre = Llibre::findOrFail($id);

        return response()->json($llibre->autores);
    }

    /**
     * Función para obtener las categorías de un libro específico
     * @param int $id El ID del libro
     * @return \Illuminate\Http\JsonResponse La respuesta JSON con las categorías del libro
     */
    public function categorias($id)
    {
        $llibre = Llibre::findOrFail($id);

        return response()->json($llibre->categorias);
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

            $llibre->autores()->sync($autorIds);
            $llibre->categorias()->sync($categoriaIds);

            return response()->json(
                $llibre->load(['autores', 'categorias', 'idioma']),
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
        return response()->json($llibre->load(['autores', 'categorias', 'idioma']));
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

            $llibre ->update($validate);
            $llibre->autores()->sync($autorIds);
            $llibre->categorias()->sync($categoriaIds);

            return response()->json(
                $llibre->load(['autores', 'categorias', 'idioma']),
                200
            );
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Llibre $llibre)
    {
        $llibre->delete();

        //return response()->json(null, 204);
        return response()->noContent();
    }
    
    public function contarLlibres()
    {
        $totalLlibres = Llibre::count();

        return response()->json(['total' => $totalLlibres]);
    }

    public function estadisticaLecturaAcabadaONo()
    {
        $llibresLlegits = Llibre::where('leido', 'si')->count();

        $llibresNoLlegits = Llibre::where('leido', 'no')->count();

        $llibresEnProceso = Llibre::where('leido', 'en_proceso')->count();

        return response()->json([
            'leidos' => $llibresLlegits,
            'noLeidos' => $llibresNoLlegits,
            'enProceso' => $llibresEnProceso
        ]);
    }
}
