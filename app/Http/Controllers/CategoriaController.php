<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoriaRequest;

use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Categoria::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request)
    {
        $validate = $request->validated();

        Categoria::create($validate);

        return redirect()->back()->with('success', 'Categoria creat correctament');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoria = Categoria::findOrFail($id);

        return response()->json($categoria);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoriaRequest $request, Categoria $categoria)
    {
        $validate = $request->validated();

        //$categoria = Categoria::findOrFail($categoria->id);

        $categoria->update($validate);

        //return redirect()->back()->with('success', 'Categoria actualitzada correctament');
        return response()->json($categoria->fresh(), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return redirect()->back()->with('success', 'Categoria eliminada correctament');
    }
}
