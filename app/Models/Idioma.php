<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Idioma extends Model
{
    protected $table = 'idiomas';

    protected $fillable = [
        'idioma' => 'required|string|max:255',
    ];

    //Un idioma puede tener muchos libros
    public function llibres()
    {
        return $this->hasMany(Llibre::class, 'idioma_id');
    }
}
