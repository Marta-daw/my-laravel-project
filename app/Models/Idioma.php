<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Idioma extends Model
{
    use HasFactory;
    
    protected $table = 'idiomas';

    protected $fillable = [
        'idioma',
    ];

    //Un idioma puede tener muchos libros
    public function llibres()
    {
        return $this->hasMany(Llibre::class, 'idioma_id');
    }
}
