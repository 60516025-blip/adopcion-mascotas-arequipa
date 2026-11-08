<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'inicio')->name('inicio');

// Catálogo público
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo');
Route::get('/mascotas/{id}', [CatalogoController::class, 'show'])->whereNumber('id')->name('mascotas.show');

// Solo para usuarios sin sesión
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarAcceso'])->name('login');
    Route::get('/registro', [AuthController::class, 'mostrarAcceso'])->name('registro');
    // Máximo 5 intentos por minuto (protege contra fuerza bruta)
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.enviar');
    Route::post('/registro', [AuthController::class, 'registrar'])->name('registro.enviar');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');