<?php

use App\Http\Controllers\Albergue\MascotaController;
use App\Http\Controllers\Albergue\PanelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Albergue\PerfilController;
use App\Http\Controllers\Albergue\SolicitudController as SolicitudAlbergueController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\Admin\AlbergueController as AdminAlbergueController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PublicacionController as AdminPublicacionController;
use App\Http\Controllers\Admin\UsuarioController as AdminUsuarioController;
use App\Http\Controllers\InicioController;

Route::get('/', [InicioController::class, 'index'])->name('inicio');

// Catálogo público
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo');
Route::get('/mascotas/{id}', [CatalogoController::class, 'show'])->whereNumber('id')->name('mascotas.show');

// Solo para usuarios sin sesión
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
    // Máximo 5 intentos por minuto (protege contra fuerza bruta)
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.enviar');
    Route::post('/registro', [AuthController::class, 'registrar'])->name('registro.enviar');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Panel del albergue: requiere sesión y rol albergue
Route::middleware(['auth', 'rol:albergue'])->prefix('albergue')->name('albergue.')->group(function () {
    Route::get('/', [PanelController::class, 'index'])->name('panel');
    Route::get('/mascotas/crear', [MascotaController::class, 'create'])->name('mascotas.create');
    Route::post('/mascotas', [MascotaController::class, 'store'])->name('mascotas.store');
    Route::get('/mascotas/{mascota}/editar', [MascotaController::class, 'edit'])->name('mascotas.edit');
    Route::put('/mascotas/{mascota}', [MascotaController::class, 'update'])->name('mascotas.update');
    Route::delete('/mascotas/{mascota}', [MascotaController::class, 'destroy'])->name('mascotas.destroy');
    Route::patch('/mascotas/{mascota}/estado', [MascotaController::class, 'cambiarEstado'])->name('mascotas.estado');
    // Solicitudes recibidas (RF07) y perfil del albergue (RF02)
    Route::get('/solicitudes', [SolicitudAlbergueController::class, 'index'])->name('solicitudes.index');
    Route::get('/solicitudes/{solicitud}', [SolicitudAlbergueController::class, 'show'])->name('solicitudes.show');
    Route::patch('/solicitudes/{solicitud}', [SolicitudAlbergueController::class, 'responder'])->name('solicitudes.responder');
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});

// Adoptante: preadopción (RF06) y seguimiento (RF08)
Route::middleware(['auth', 'rol:adoptante'])->group(function () {
    Route::get('/mascotas/{mascota}/adoptar', [SolicitudController::class, 'create'])->whereNumber('mascota')->name('solicitudes.create');
    Route::post('/mascotas/{mascota}/adoptar', [SolicitudController::class, 'store'])->whereNumber('mascota')->name('solicitudes.store');
    Route::get('/mis-solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index');
});

// Administrador (RF09 y RF10)
Route::middleware(['auth', 'rol:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/albergues', [AdminAlbergueController::class, 'index'])->name('albergues.index');
    Route::patch('/albergues/{albergue}', [AdminAlbergueController::class, 'actualizar'])->name('albergues.actualizar');
    Route::get('/usuarios', [AdminUsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/publicaciones', [AdminPublicacionController::class, 'index'])->name('publicaciones.index');
    Route::delete('/publicaciones/{mascota}', [AdminPublicacionController::class, 'destroy'])->name('publicaciones.destroy');
});