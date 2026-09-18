<?php

use Illuminate\Support\Facades\Route;

// Point d'entrée de l'application SPA (Vue Router gère les routes côté client).
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');