<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitudRequest;
use App\Models\Mascota;
use App\Models\Solicitud;
use App\Services\SolicitudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Preadopción (RF06) y seguimiento de solicitudes del adoptante (RF08).
 */
class SolicitudController extends Controller
{
    public function __construct(private SolicitudService $servicio)
    {
    }

    /** Mis solicitudes (RF08). */
    public function index(Request $request)
    {
        $solicitudes = Solicitud::where('adoptante_id', $request->user()->id)
            ->with('mascota.albergue')
            ->latest()
            ->paginate(10);

        return view('solicitudes.index', compact('solicitudes'));
    }

    /** Formulario de preadopción en 4 pasos. */
    public function create(Request $request, int $mascota)
    {
        $mascota = $this->mascotaVisible($mascota);

        if ($motivo = $this->servicio->motivoBloqueo($mascota, $request->user())) {
            return redirect()->route('mascotas.show', $mascota->id)->with('error', $motivo);
        }

        return view('solicitudes.create', compact('mascota'));
    }

    public function store(SolicitudRequest $request, int $mascota): RedirectResponse
    {
        $mascota = $this->mascotaVisible($mascota);

        if ($motivo = $this->servicio->motivoBloqueo($mascota, $request->user())) {
            return redirect()->route('mascotas.show', $mascota->id)->with('error', $motivo);
        }

        $this->servicio->crear($mascota, $request->user(), $request->datosSolicitud());

        return redirect()->route('solicitudes.index')
            ->with('exito', 'Solicitud enviada. El albergue la revisará y verás su respuesta aquí.');
    }

    /** Solo mascotas de albergues validados (mismo criterio del catálogo). */
    private function mascotaVisible(int $id): Mascota
    {
        return Mascota::visibles()->with(['albergue', 'fichaMedica'])->findOrFail($id);
    }
}