<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_1' => 'required|string|max:255',
            'apellido_2' => 'nullable|string|max:255',
        ]);

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
    public function update(Request $request, Autor $autor)
    {
        $validate = $request->validate([
            $autor->id => 'required|exists:autors,id',
            'nombre' => 'required|string|max:255',
            'apellido_1' => 'required|string|max:255',
            'apellido_2' => 'nullable|string|max:255',
        ]);
        
        $autor = Autor::findOrFail($autor->id);
        $autor->update($validate);

        return redirect()->back()->with('success', 'Autor actualitzat correctament');
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
