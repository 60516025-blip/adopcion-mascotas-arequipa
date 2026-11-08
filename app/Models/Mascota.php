<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mascota extends Model
{
    protected $fillable = [
        'albergue_id', 'nombre', 'especie', 'raza', 'edad_meses',
        'tamano', 'genero', 'descripcion', 'estado', 'foto_url',
    ];

    public function albergue(): BelongsTo
    {
        return $this->belongsTo(Albergue::class);
    }

    /** Ficha sanitaria (vacunas, desparasitación, esterilización). */
    public function fichaMedica(): HasOne
    {
        return $this->hasOne(FichaMedica::class);
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }
}