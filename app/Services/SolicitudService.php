<?php

namespace App\Services;

use App\Models\Mascota;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Reglas de negocio de las solicitudes de adopción.
 */
class SolicitudService
{
    /** Devuelve el motivo por el que NO se puede solicitar, o null si se puede. */
    public function motivoBloqueo(Mascota $mascota, User $adoptante): ?string
    {
        // Regla: solo las mascotas disponibles reciben solicitudes
        if ($mascota->estado !== 'disponible') {
            return 'Esta mascota ya no está disponible para adopción.';
        }

        // Regla: una sola solicitud pendiente por adoptante y mascota
        $pendiente = Solicitud::where('mascota_id', $mascota->id)
            ->where('adoptante_id', $adoptante->id)
            ->where('estado', 'pendiente')
            ->exists();

        return $pendiente ? 'Ya tienes una solicitud pendiente para esta mascota.' : null;
    }

    public function crear(Mascota $mascota, User $adoptante, array $datos): Solicitud
    {
        return Solicitud::create($datos + [
            'mascota_id' => $mascota->id,
            'adoptante_id' => $adoptante->id,
            'estado' => 'pendiente',
        ]);
    }

    /**
     * Aprueba o rechaza una solicitud pendiente.
     * Al aprobar, la mascota pasa a "en_proceso".
     * Devuelve un mensaje de error o null si todo salió bien.
     */
    public function responder(Solicitud $solicitud, string $decision, ?string $respuesta): ?string
    {
        if ($solicitud->estado !== 'pendiente') {
            return 'Esta solicitud ya fue respondida.';
        }

        return DB::transaction(function () use ($solicitud, $decision, $respuesta) {
            $mascota = $solicitud->mascota;

            if ($decision === 'aprobada') {
                if ($mascota->estado !== 'disponible') {
                    return 'La mascota ya no está disponible; no se puede aprobar.';
                }
                $mascota->update(['estado' => 'en_proceso']);
            }

            $solicitud->update(['estado' => $decision, 'respuesta' => $respuesta]);

            return null;
        });
    }
}