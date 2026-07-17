<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $table = 'autors';

    protected $fillable = [
        'nombre' => 'required|string|max:255',
        'apellido_1' => 'required|string|max:255',
        'apellido_2' => 'nullable|string|max:255',
    ];

    //Un autor puede tener muchos libros
    public function llibres()
    {
        return $this->belongsToMany(Llibre::class, 'autor_llibre', 'autor_id', 'llibre_id');
    }
}
