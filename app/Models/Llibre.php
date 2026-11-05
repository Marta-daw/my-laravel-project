<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Llibre extends Model
{
    use HasFactory;

    protected $appends = ['portada_url'];
    
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

    public function getPortadaUrlAttribute(): ?string
    {
        $portada = $this->getRawOriginal('portada');

        if (!$portada) {
            return null;
        }

        if (filter_var($portada, FILTER_VALIDATE_URL)) {
            return $portada;
        }

        $path = str_starts_with($portada, 'portades/')
            ? $portada
            : 'portades/' . ltrim($portada, '/');

        return Storage::disk('public')->url($path);
    }

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
