<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreIdiomaRequest;

use App\Models\Idioma;

class IdiomaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Idioma::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIdiomaRequest $request)
    {
        $idioma = Idioma::create($request->validated());

        return response()->json($idioma, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $idioma = Idioma::findOrFail($id);

        return response()->json($idioma);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreIdiomaRequest $request, Idioma $idioma)
    {
        $validate = $request->validated();

        //$idioma= Idioma::findOrFail($idioma->id);
        $idioma->update($validate);

        //return redirect()->back()->with('success', 'Idioma actualitzat correctament');
        return response()->json($idioma->fresh(), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idioma $idioma)
    {
        $idioma->delete();

        //return redirect()->back()->with('success', 'Idioma eliminat correctament');
        //return response()->json(null, 204);
        return response()->noContent();
    }
}
