<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    protected $fillable = [
        'mascota_id', 'adoptante_id', 'numero_personas', 'tipo_vivienda',
        'tiene_patio', 'permiso_mascotas', 'experiencia_previa',
        'acepta_compromiso', 'estado', 'respuesta',
    ];

    protected $casts = [
        'tiene_patio' => 'boolean',
        'permiso_mascotas' => 'boolean',
        'acepta_compromiso' => 'boolean',
    ];

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class);
    }

    /** Usuario adoptante que envió la solicitud. */
    public function adoptante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adoptante_id');
    }

    /** Preevaluación orientativa (Fig. 9). La decisión final siempre la toma el albergue. */
    public function getPreevaluacionAttribute(): string
    {
        $viviendaOk = $this->tipo_vivienda === 'propia' || $this->permiso_mascotas === true;

        return ($viviendaOk && $this->acepta_compromiso) ? 'Apto' : 'Revisar';
    }
}