<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('/{any}', function () {
    return view('app'); // el nom de la vista blade principal (revisa quin nom té la teva)
})->where('any', '.*');