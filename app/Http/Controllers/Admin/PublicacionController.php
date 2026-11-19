<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mascota;
use App\Services\MascotaService;
use Illuminate\Http\RedirectResponse;

/**
 * Moderación de publicaciones (RF09): el administrador puede retirar mascotas.
 */
class PublicacionController extends Controller
{
    public function __construct(private MascotaService $servicio)
    {
    }

    public function index()
    {
        $mascotas = Mascota::with('albergue')->orderByDesc('id')->paginate(15);

        return view('admin.publicaciones.index', compact('mascotas'));
    }

    public function destroy(Mascota $mascota): RedirectResponse
    {
        $this->servicio->eliminar($mascota);

        return back()->with('exito', 'Publicación eliminada.');
    }
}