<?php

namespace App\Http\Controllers;

use App\Models\Albergue;
use Illuminate\Http\Request;

/**
 * Páginas públicas de albergues: lista con filtro por distrito y perfil de cada uno.
 * Solo se muestran los albergues validados por el administrador.
 */
class AlberguePublicoController extends Controller
{
    public function index(Request $request)
    {
        $distrito = $request->query('distrito');
        $miDistrito = $request->user()?->distrito;

        $distritos = Albergue::where('validado', true)
            ->where('distrito', '!=', 'Por definir')
            ->distinct()
            ->orderBy('distrito')
            ->pluck('distrito');

        // withCount evita consultar las mascotas una por una (problema N+1)
        $albergues = Albergue::where('validado', true)
            ->withCount(['mascotas as disponibles_count' => fn ($q) => $q->where('estado', 'disponible')])
            ->when($distrito && $distritos->contains($distrito), fn ($q) => $q->where('distrito', $distrito))
            // Sin filtro, los albergues del distrito del usuario salen primero
            ->when($miDistrito && ! $distrito, fn ($q) => $q->orderByRaw('distrito = ? desc', [$miDistrito]))
            ->orderBy('nombre')
            ->paginate(9)
            ->withQueryString();

        $cercanos = $miDistrito && ! $distrito && $albergues->contains('distrito', $miDistrito);

        return view('albergues.index', compact('albergues', 'distritos', 'distrito', 'miDistrito', 'cercanos'));
    }

    public function show(Albergue $albergue)
    {
        abort_unless($albergue->validado, 404);

        $mascotas = $albergue->mascotas()
            ->where('estado', 'disponible')
            ->with('fichaMedica')
            ->latest('id')
            ->paginate(9);

        return view('albergues.show', compact('albergue', 'mascotas'));
    }
}