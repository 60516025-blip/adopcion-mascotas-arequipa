<?php

namespace App\Services;

use App\Models\Albergue;
use App\Models\Mascota;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Lógica de negocio de las mascotas. Mantiene delgados los controladores.
 */
class MascotaService
{
    /** Cambios de estado permitidos. */
    private const TRANSICIONES = [
        'disponible' => ['en_proceso'],
        'en_proceso' => ['adoptada', 'disponible'],
        'adoptada' => [],
    ];

    /** Crea la mascota junto con su ficha sanitaria (regla: toda mascota tiene ficha). */
    public function crear(Albergue $albergue, array $datos, array $ficha, ?UploadedFile $foto): Mascota
    {
        return DB::transaction(function () use ($albergue, $datos, $ficha, $foto) {
            if ($foto) {
                $datos['foto_url'] = $this->guardarFoto($foto);
            }

            $mascota = $albergue->mascotas()->create($datos);
            $mascota->fichaMedica()->create($ficha);

            return $mascota;
        });
    }

    /** Actualiza la mascota y su ficha; reemplaza la foto si se sube otra. */
    public function actualizar(Mascota $mascota, array $datos, array $ficha, ?UploadedFile $foto): Mascota
    {
        return DB::transaction(function () use ($mascota, $datos, $ficha, $foto) {
            if ($foto) {
                $this->borrarFoto($mascota->foto_url);
                $datos['foto_url'] = $this->guardarFoto($foto);
            }

            $mascota->update($datos);
            $mascota->fichaMedica()->updateOrCreate(['mascota_id' => $mascota->id], $ficha);

            return $mascota;
        });
    }

    /** Elimina la mascota y su foto (la ficha se borra en cascada). */
    public function eliminar(Mascota $mascota): void
    {
        $this->borrarFoto($mascota->foto_url);
        $mascota->delete();
    }

    /** Cambia el estado solo si la transición es válida. */
    public function cambiarEstado(Mascota $mascota, string $nuevo): bool
    {
        if (! in_array($nuevo, self::TRANSICIONES[$mascota->estado] ?? [], true)) {
            return false;
        }

        $mascota->update(['estado' => $nuevo]);

        return true;
    }

    /** Guarda la foto con nombre aleatorio en storage/app/public/mascotas. */
    private function guardarFoto(UploadedFile $foto): string
    {
        return 'storage/' . $foto->store('mascotas', 'public');
    }

    private function borrarFoto(?string $url): void
    {
        if ($url) {
            Storage::disk('public')->delete(Str::after($url, 'storage/'));
        }
    }
}