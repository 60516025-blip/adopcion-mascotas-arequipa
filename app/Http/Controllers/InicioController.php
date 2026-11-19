<?php

namespace App\Http\Controllers;

use App\Models\Albergue;
use App\Models\Mascota;
use Illuminate\Support\Facades\Cache;

/**
 * Página de inicio pública: cifras reales y mascotas destacadas.
 */
class InicioController extends Controller
{
    public function index()
    {
        // Cache::remember evita repetir los conteos en cada visita (se renuevan cada 60 s)
        $stats = Cache::remember('inicio.stats', 60, fn () => [
            'disponibles' => Mascota::visibles()->where('estado', 'disponible')->count(),
            'albergues' => Albergue::where('validado', true)->count(),
            'adopciones' => Mascota::where('estado', 'adoptada')->count(),
        ]);

        // with() evita el problema N+1 al mostrar albergue y ficha de cada tarjeta
        $destacadas = Mascota::visibles()
            ->where('estado', 'disponible')
            ->with(['albergue:id,nombre,distrito', 'fichaMedica'])
            ->latest('id')
            ->take(3)
            ->get();

        return view('inicio', compact('stats', 'destacadas'));
    }
}