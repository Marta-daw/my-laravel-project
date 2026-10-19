<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;
    
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
