<?php

namespace App\Http\Controllers\Albergue;

use App\Http\Controllers\Controller;
use App\Http\Requests\MascotaRequest;
use App\Models\Mascota;
use App\Services\MascotaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * CRUD de mascotas del albergue (RF03). La lógica vive en MascotaService
 * y la autorización en MascotaPolicy.
 */
class MascotaController extends Controller
{
    public function __construct(private MascotaService $servicio)
    {
    }

    public function create()
    {
        if (Gate::denies('create', Mascota::class)) {
            return $this->noValidado();
        }

        return view('albergue.mascotas.form', ['mascota' => new Mascota()]);
    }

    public function store(MascotaRequest $request): RedirectResponse
    {
        if (Gate::denies('create', Mascota::class)) {
            return $this->noValidado();
        }

        $this->servicio->crear(
            $request->user()->albergue,
            $request->datosMascota(),
            $request->datosFicha(),
            $request->file('foto')
        );

        return redirect()->route('albergue.panel')->with('exito', 'Mascota publicada correctamente.');
    }

    public function edit(Mascota $mascota)
    {
        Gate::authorize('update', $mascota); // verificación de propiedad

        return view('albergue.mascotas.form', ['mascota' => $mascota->load('fichaMedica')]);
    }

    public function update(MascotaRequest $request, Mascota $mascota): RedirectResponse
    {
        Gate::authorize('update', $mascota);

        $this->servicio->actualizar(
            $mascota,
            $request->datosMascota(),
            $request->datosFicha(),
            $request->file('foto')
        );

        return redirect()->route('albergue.panel')->with('exito', 'Mascota actualizada.');
    }

    public function destroy(Mascota $mascota): RedirectResponse
    {
        Gate::authorize('delete', $mascota);

        $this->servicio->eliminar($mascota);

        return redirect()->route('albergue.panel')->with('exito', 'Mascota eliminada.');
    }

    /** Cambio de estado con un clic (disponible, en proceso, adoptada). */
    public function cambiarEstado(Request $request, Mascota $mascota): RedirectResponse
    {
        Gate::authorize('update', $mascota);

        $datos = $request->validate(['estado' => ['required', 'in:disponible,en_proceso,adoptada']]);

        if (! $this->servicio->cambiarEstado($mascota, $datos['estado'])) {
            return back()->with('error', 'Ese cambio de estado no está permitido.');
        }

        return back()->with('exito', 'Estado actualizado.');
    }

    private function noValidado(): RedirectResponse
    {
        return redirect()->route('albergue.panel')
            ->with('error', 'Tu albergue aún no ha sido validado por el administrador.');
    }
}