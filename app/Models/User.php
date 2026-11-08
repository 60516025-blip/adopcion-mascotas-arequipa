<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['name', 'email', 'password', 'rol', 'telefono', 'direccion', 'distrito'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** Indica si el usuario es administrador de la plataforma. */
    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    /** Indica si el usuario es un albergue o rescatista. */
    public function esAlbergue(): bool
    {
        return $this->rol === 'albergue';
    }

    /** Indica si el usuario es un adoptante. */
    public function esAdoptante(): bool
    {
        return $this->rol === 'adoptante';
    }

    /** Albergue que administra este usuario (solo rol albergue). */
    public function albergue(): HasOne
    {
        return $this->hasOne(Albergue::class);
    }

    /** Solicitudes de adopción enviadas por este usuario. */
    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'adoptante_id');
    }
}
