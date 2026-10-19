<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Llibre extends Model
{
    use HasFactory;
    
    protected $table = 'llibres';

    protected $fillable = [
        'titulo',
        'paginas',
        'sinopsis',
        'idioma_id',
        'leido',
        'puntuacion',
        'portada',
    ];

    //Un libro tiene un idioma
    public function idioma()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }

    //Un libro puede tener varias categorias
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_llibre', 'llibre_id', 'categoria_id');
    }

    //Un libro puede tener varios autores
    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'autor_llibre', 'llibre_id', 'autor_id');
    }
}
