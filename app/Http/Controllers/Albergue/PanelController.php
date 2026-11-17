<?php

namespace App\Http\Controllers\Albergue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Panel del albergue (Fig. 9). Controlador delgado.
 */
class PanelController extends Controller
{
    public function index(Request $request)
    {
        $albergue = $request->user()->albergue;

        // with() evita el problema N+1 al mostrar la ficha de cada mascota
        $mascotas = $albergue->mascotas()
            ->with('fichaMedica')
            ->orderByDesc('id')
            ->paginate(10);

        return view('albergue.panel', compact('albergue', 'mascotas'));
    }
}