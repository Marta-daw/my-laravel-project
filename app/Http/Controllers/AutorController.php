<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAutorRequest;

use App\Models\Autor;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Autor::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAutorRequest $request)
    {
        $validate = $request->validated();

        Autor::create($validate);

        return redirect()->back()->with('success', 'Autor creat correctament');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $autor = Autor::findOrFail($id);

        return response()->json($autor);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAutorRequest $request, Autor $autor)
    {
        $validate = $request->validated();
        
        //$autor = Autor::findOrFail($autor->id);
        $autor->update($validate);

        //return redirect()->back()->with('success', 'Autor actualitzat correctament');
        return response()->json($autor->fresh(), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Autor $autor)
    {
        $autor->delete();

        return redirect()->back()->with('success', 'Autor eliminat correctament');
    }
}
