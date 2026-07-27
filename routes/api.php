<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\IdiomaController;
use App\Http\Controllers\LlibreController;
// Capceleres que permet l'accés des de qualsevol lloc i retorna JSON
// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Methods: *");
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: *");

//Obtenim el mètode HTTP de la petició utilitzat
// $method = $_SERVER['REQUEST_METHOD'];

//Rutes públiques per a la gestió d'autors
Route::get('/autors', [AutorController::class, 'index']);
Route::get('/autors/{id}', [AutorController::class, 'show']);
Route::post('/autors', [AutorController::class, 'store']);
Route::put('/autors/{id}', [AutorController::class, 'update']);
Route::delete('/autors/{id}', [AutorController::class, 'destroy']);

// Rutes públiques per a la gestió de categories
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);
Route::post('/categorias', [CategoriaController::class, 'store']);
Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);

// Rutes públiques per a la gestió d'idiomes
Route::get('/idiomas', [IdiomaController::class, 'index']);
Route::get('/idiomas/{id}', [IdiomaController::class, 'show']);
Route::post('/idiomas', [IdiomaController::class, 'store']);
Route::put('/idiomas/{id}', [IdiomaController::class, 'update']);
Route::delete('/idiomas/{id}', [IdiomaController::class, 'destroy']);

// Rutes públiques per a la gestió de llibres
Route::get('/llibres', [LlibreController::class, 'index']);
Route::get('/llibres/{id}', [LlibreController::class, 'show']);
Route::post('/llibres', [LlibreController::class, 'store']);
Route::put('/llibres/{id}', [LlibreController::class, 'update']);
Route::delete('/llibres/{id}', [LlibreController::class, 'destroy']);