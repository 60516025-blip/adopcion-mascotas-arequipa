<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogoRequest;
use App\Models\Mascota;

/**
 * Catálogo público de mascotas. Controlador delgado: la lógica de
 * filtrado vive en los scopes del modelo Mascota.
 */
class CatalogoController extends Controller
{
    /** Lista paginada con búsqueda y filtros (RF04). */
    public function index(CatalogoRequest $request)
    {
        $filtros = $request->validated();

        $mascotas = Mascota::visibles()
            // Carga previa de relaciones: evita el problema N+1
            ->with(['albergue:id,nombre,distrito', 'fichaMedica'])
            ->filtrar($filtros)
            ->orderByDesc('id')
            ->paginate(6)
            ->withQueryString(); // conserva los filtros al cambiar de página

        return view('catalogo.index', compact('mascotas', 'filtros'));
    }

    /** Ficha detallada de una mascota (RF05). */
    public function show(int $id)
    {
        $mascota = Mascota::visibles()
            ->with(['albergue', 'fichaMedica'])
            ->findOrFail($id);

        return view('catalogo.show', compact('mascota'));
    }
}