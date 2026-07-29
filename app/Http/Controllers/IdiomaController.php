<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
        $validate = $request->validate([
            'idioma' => 'required|string|max:255',
        ]);

        Idioma::create($validate);

        return redirect()->back()->with('success', 'Idioma creat correctament');
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
    public function update(Request $request, Idioma $idioma)
    {
        $validate = $request->validate([
            $idioma->id => 'required|exists:idiomas,id',
            'idioma' => 'required|string|max:255',
        ]);

        $idioma= Idioma::findOrFail($idioma->id);
        $idioma->update($validate);

        return redirect()->back()->with('success', 'Idioma actualitzat correctament');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idioma $idioma)
    {
        $idioma->delete();

        return redirect()->back()->with('success', 'Idioma eliminat correctament');
    }
}
