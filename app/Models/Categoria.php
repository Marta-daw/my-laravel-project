<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
    ];

    //Una categoria puede tener muchos libros
    public function llibres()
    {
        return $this->belongsToMany(Llibre::class, 'categoria_llibre', 'categoria_id', 'llibre_id');
    }
}
