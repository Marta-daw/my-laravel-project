<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Llibre extends Model
{
    protected $table = 'llibres';

    protected $fillable = [
        'titulo',
        'páginas',
        'sinopsis',
        'idioma_id',
        'leido',
        'puntuación',
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
    public function autors()
    {
        return $this->belongsToMany(Autor::class, 'autor_llibre', 'llibre_id', 'autor_id');
    }
}
