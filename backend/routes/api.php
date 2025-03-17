<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Otras rutas protegidas por Sanctum
    Route::get('/profile', function (Request $request) {
        return $request->user()->profile;
    });
});

// Rutas públicas
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
