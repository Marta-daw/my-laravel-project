<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\IdiomaController;
use App\Http\Controllers\LlibreController;

//Rutes públiques per a la gestió d'autors
Route::get('/autors', [AutorController::class, 'index']);
Route::get('/autors/{autor}', [AutorController::class, 'show']);
Route::post('/autors', [AutorController::class, 'store']);
Route::put('/autors/{autor}', [AutorController::class, 'update']);
Route::delete('/autors/{autor}', [AutorController::class, 'destroy']);

// Rutes públiques per a la gestió de categories
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/categorias/{categoria}', [CategoriaController::class, 'show']);
Route::post('/categorias', [CategoriaController::class, 'store']);
Route::put('/categorias/{categoria}', [CategoriaController::class, 'update']);
Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy']);

// Rutes públiques per a la gestió d'idiomes
Route::get('/idiomas', [IdiomaController::class, 'index']);
Route::get('/idiomas/{idioma}', [IdiomaController::class, 'show']);
Route::post('/idiomas', [IdiomaController::class, 'store']);
Route::put('/idiomas/{idioma}', [IdiomaController::class, 'update']);
Route::delete('/idiomas/{idioma}', [IdiomaController::class, 'destroy']);

// Rutes públiques per a la gestió de llibres
Route::get('/llibres', [LlibreController::class, 'index']);
Route::get('/llibres/count', [LlibreController::class, 'contarLlibres']);
Route::get('/llibres/lectures', [LlibreController::class, 'estadisticaLecturaAcabadaONo']);
Route::get('/llibres/ultimos', [LlibreController::class, 'ultimosLibros']);
Route::get('/llibres/{llibre}', [LlibreController::class, 'show']);
Route::post('/llibres', [LlibreController::class, 'store']);
Route::put('/llibres/{llibre}', [LlibreController::class, 'update']);
Route::delete('/llibres/{llibre}', [LlibreController::class, 'destroy']);

// Rutes públiques de la taula pivot autor_llibre
Route::get('/llibres/{llibre}/autors', [LlibreController::class, 'autors']);