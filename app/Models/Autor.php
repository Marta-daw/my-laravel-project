<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $table = 'autors';

    protected $fillable = [
        'nombre',
        'apellido_1',
        'apellido_2',
    ];

    //Un autor puede tener muchos libros
    public function llibres()
    {
        return $this->belongsToMany(Llibre::class, 'autor_llibre', 'autor_id', 'llibre_id');
    }
}
