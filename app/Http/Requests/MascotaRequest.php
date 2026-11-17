<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida el formulario de mascota (crear y editar) con su ficha sanitaria.
 */
class MascotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // la autorización se hace con MascotaPolicy en el controlador
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'especie' => ['required', 'in:perro,gato'],
            'raza' => ['nullable', 'string', 'max:100'],
            'edad_meses' => ['required', 'integer', 'min:0', 'max:300'],
            'tamano' => ['required', 'in:pequeno,mediano,grande'],
            'genero' => ['required', 'in:macho,hembra'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            // Seguridad de archivos: solo imágenes JPG/PNG/WEBP de hasta 2 MB
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'observaciones' => ['nullable', 'string', 'max:500'],
            'fecha_ultima_revision' => ['nullable', 'date', 'before_or_equal:today'],
        ];
    }

    /** Datos de la mascota (sin ficha ni foto). */
    public function datosMascota(): array
    {
        return $this->safe()->only([
            'nombre', 'especie', 'raza', 'edad_meses', 'tamano', 'genero', 'descripcion',
        ]);
    }

    /** Datos de la ficha sanitaria; las casillas sin marcar llegan como false. */
    public function datosFicha(): array
    {
        return array_merge(
            [
                'esterilizado' => $this->boolean('esterilizado'),
                'vacuna_antirrabica' => $this->boolean('vacuna_antirrabica'),
                'vacuna_quintuple' => $this->boolean('vacuna_quintuple'),
                'desparasitado' => $this->boolean('desparasitado'),
            ],
            $this->safe()->only(['observaciones', 'fecha_ultima_revision'])
        );
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'in' => 'Selecciona una opción válida en :attribute.',
            'integer' => 'El campo :attribute debe ser un número entero.',
            'min' => 'El campo :attribute no puede ser menor que :min.',
            'max.string' => 'El campo :attribute no debe superar :max caracteres.',
            'max.numeric' => 'El campo :attribute no puede ser mayor que :max.',
            'max.file' => 'La foto no debe superar los 2 MB.',
            'image' => 'El archivo debe ser una imagen.',
            'mimes' => 'La foto debe ser JPG, PNG o WEBP.',
            'uploaded' => 'No se pudo subir la foto (revisa que no pase de 2 MB).',
            'date' => 'Ingresa una fecha válida.',
            'before_or_equal' => 'La fecha no puede ser futura.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'especie' => 'especie',
            'edad_meses' => 'edad',
            'tamano' => 'tamaño',
            'genero' => 'sexo',
            'descripcion' => 'descripción',
            'observaciones' => 'observaciones',
            'fecha_ultima_revision' => 'fecha de última revisión',
        ];
    }
}