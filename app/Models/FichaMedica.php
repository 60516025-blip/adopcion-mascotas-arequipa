<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FichaMedica extends Model
{
    // Laravel pluralizaría mal este nombre, por eso se indica la tabla
    protected $table = 'fichas_medicas';

    protected $fillable = [
        'mascota_id', 'esterilizado', 'vacuna_antirrabica', 'vacuna_quintuple',
        'desparasitado', 'observaciones', 'fecha_ultima_revision',
    ];

    protected $casts = [
        'esterilizado' => 'boolean',
        'vacuna_antirrabica' => 'boolean',
        'vacuna_quintuple' => 'boolean',
        'desparasitado' => 'boolean',
        'fecha_ultima_revision' => 'date',
    ];

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class);
    }
}