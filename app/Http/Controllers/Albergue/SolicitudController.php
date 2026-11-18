<?php

namespace App\Http\Controllers\Albergue;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Services\SolicitudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Solicitudes recibidas por el albergue: listar, ver detalle, aprobar o rechazar.
 */
class SolicitudController extends Controller
{
    public function __construct(private SolicitudService $servicio)
    {
    }

    public function index(Request $request)
    {
        $estado = $request->query('estado');
        $albergueId = $request->user()->albergue->id;

        $solicitudes = Solicitud::whereHas('mascota', fn ($q) => $q->where('albergue_id', $albergueId))
            ->when(
                in_array($estado, ['pendiente', 'aprobada', 'rechazada'], true),
                fn ($q) => $q->where('estado', $estado)
            )
            ->with(['mascota', 'adoptante'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('albergue.solicitudes.index', compact('solicitudes', 'estado'));
    }

    public function show(Solicitud $solicitud)
    {
        Gate::authorize('view', $solicitud); // verificación de propiedad

        return view('albergue.solicitudes.show', [
            'solicitud' => $solicitud->load(['mascota.fichaMedica', 'adoptante']),
        ]);
    }

    public function responder(Request $request, Solicitud $solicitud): RedirectResponse
    {
        Gate::authorize('responder', $solicitud);

        $datos = $request->validate([
            'decision' => ['required', 'in:aprobada,rechazada'],
            'respuesta' => ['nullable', 'required_if:decision,rechazada', 'string', 'max:500'],
        ], [
            'decision.required' => 'Elige aprobar o rechazar.',
            'decision.in' => 'Elige aprobar o rechazar.',
            'respuesta.required_if' => 'Escribe un mensaje para el adoptante explicando el motivo.',
            'respuesta.max' => 'El mensaje no debe superar :max caracteres.',
        ]);

        $error = $this->servicio->responder($solicitud, $datos['decision'], $datos['respuesta'] ?? null);

        if ($error) {
            return back()->with('error', $error);
        }

        return redirect()->route('albergue.solicitudes.index')->with(
            'exito',
            $datos['decision'] === 'aprobada'
                ? 'Solicitud aprobada. La mascota pasó a "En proceso".'
                : 'Solicitud rechazada.'
        );
    }
}