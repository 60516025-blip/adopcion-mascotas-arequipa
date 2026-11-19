<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Albergue;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Validación de albergues por el administrador (RF09).
 */
class AlbergueController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->query('estado');

        $albergues = Albergue::withCount('mascotas')
            ->when($estado === 'pendiente', fn ($q) => $q->where('validado', false))
            ->when($estado === 'validado', fn ($q) => $q->where('validado', true))
            ->orderBy('validado')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Cuenta responsable de cada albergue, cargada en una sola consulta
        $responsables = User::whereIn('id', $albergues->pluck('user_id'))->get()->keyBy('id');

        return view('admin.albergues.index', compact('albergues', 'responsables', 'estado'));
    }

    public function actualizar(Request $request, Albergue $albergue): RedirectResponse
    {
        $datos = $request->validate(['validado' => ['required', 'boolean']]);

        // forceFill: "validado" no es asignable en masa
        $albergue->forceFill(['validado' => (bool) $datos['validado']])->save();

        return back()->with('exito', $albergue->validado
            ? 'Albergue validado: ya puede publicar mascotas.'
            : 'Validación retirada: el albergue ya no puede publicar y sus mascotas salen del catálogo.');
    }
}