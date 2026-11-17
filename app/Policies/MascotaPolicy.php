<?php

namespace App\Policies;

use App\Models\Mascota;
use App\Models\User;

/**
 * Autorización sobre mascotas: solo el albergue dueño puede modificarlas
 * y solo un albergue validado por el administrador puede publicar.
 */
class MascotaPolicy
{
    /** Publicar: usuario albergue con albergue validado. */
    public function create(User $user): bool
    {
        return $user->esAlbergue() && $user->albergue?->validado === true;
    }

    /** Editar o cambiar estado: solo el albergue propietario. */
    public function update(User $user, Mascota $mascota): bool
    {
        return $user->esAlbergue() && $user->albergue?->id === $mascota->albergue_id;
    }

    public function delete(User $user, Mascota $mascota): bool
    {
        return $this->update($user, $mascota);
    }
}