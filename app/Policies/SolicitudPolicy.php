<?php

namespace App\Policies;

use App\Models\Solicitud;
use App\Models\User;

/**
 * Solo el albergue dueño de la mascota puede ver y responder sus solicitudes.
 */
class SolicitudPolicy
{
    public function view(User $user, Solicitud $solicitud): bool
    {
        return $user->esAlbergue() && $user->albergue?->id === $solicitud->mascota->albergue_id;
    }

    public function responder(User $user, Solicitud $solicitud): bool
    {
        return $this->view($user, $solicitud);
    }
}