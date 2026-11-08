<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Mascotas que se muestran al público: disponibles o en proceso,
     * y solo de albergues validados por el administrador.
     */
    public function scopeVisibles(Builder $query): Builder
    {
        return $query
            ->whereIn('estado', ['disponible', 'en_proceso'])
            ->whereHas('albergue', fn (Builder $a) => $a->where('validado', true));
    }

    /**
     * Aplica los filtros del catálogo. Cada filtro es opcional.
     * Las consultas usan parámetros enlazados (sin riesgo de inyección SQL).
     *
     * @param array{q?:string, especie?:array, tamano?:array, edad?:array, salud?:array} $f
     */
    public function scopeFiltrar(Builder $query, array $f): Builder
    {
        return $query
            // Búsqueda por nombre o raza
            ->when($f['q'] ?? null, function (Builder $q, string $texto) {
                $q->where(function (Builder $w) use ($texto) {
                    $w->where('nombre', 'like', "%{$texto}%")
                      ->orWhere('raza', 'like', "%{$texto}%");
                });
            })
            ->when($f['especie'] ?? null, fn (Builder $q, array $v) => $q->whereIn('especie', $v))
            ->when($f['tamano'] ?? null, fn (Builder $q, array $v) => $q->whereIn('tamano', $v))
            // Edad: cachorro (<12 meses), adulto (12 a 83), senior (84 o más)
            ->when($f['edad'] ?? null, function (Builder $q, array $v) {
                $q->where(function (Builder $w) use ($v) {
                    foreach ($v as $rango) {
                        match ($rango) {
                            'cachorro' => $w->orWhere('edad_meses', '<', 12),
                            'adulto' => $w->orWhereBetween('edad_meses', [12, 83]),
                            'senior' => $w->orWhere('edad_meses', '>=', 84),
                            default => null,
                        };
                    }
                });
            })
            // Salud: se consulta la ficha sanitaria
            ->when($f['salud'] ?? null, function (Builder $q, array $v) {
                $q->whereHas('fichaMedica', function (Builder $fm) use ($v) {
                    foreach ($v as $criterio) {
                        match ($criterio) {
                            'vacunada' => $fm->where('vacuna_antirrabica', true)->where('vacuna_quintuple', true),
                            'desparasitada' => $fm->where('desparasitado', true),
                            'esterilizada' => $fm->where('esterilizado', true),
                            default => null,
                        };
                    }
                });
            });
    }

    /** Edad legible: "6 meses" o "2 años". */
    public function getEdadTextoAttribute(): string
    {
        $meses = $this->edad_meses;

        if ($meses < 12) {
            return $meses . ($meses === 1 ? ' mes' : ' meses');
        }

        $anios = intdiv($meses, 12);

        return $anios . ($anios === 1 ? ' año' : ' años');
    }

    public function getTamanoTextoAttribute(): string
    {
        return ['pequeno' => 'Pequeño', 'mediano' => 'Mediano', 'grande' => 'Grande'][$this->tamano] ?? $this->tamano;
    }

    public function getEstadoTextoAttribute(): string
    {
        return ['disponible' => 'Disponible', 'en_proceso' => 'En proceso', 'adoptada' => 'Adoptada'][$this->estado] ?? $this->estado;
    }

    /** Vacunada = antirrábica y quíntuple al día en la ficha sanitaria. */
    public function getEstaVacunadaAttribute(): bool
    {
        $ficha = $this->fichaMedica;

        return $ficha && $ficha->vacuna_antirrabica && $ficha->vacuna_quintuple;
    }
}