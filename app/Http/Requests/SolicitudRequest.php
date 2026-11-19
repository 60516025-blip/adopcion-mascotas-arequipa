<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida el formulario de preadopción (4 pasos, un solo envío).
 */
class SolicitudRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // el rol se controla con el middleware de la ruta
    }

    public function rules(): array
    {
        return [
            'numero_personas' => ['required', 'integer', 'min:1', 'max:30'],
            'tipo_vivienda' => ['required', 'in:propia,alquilada'],
            'tiene_patio' => ['required', 'boolean'],
            'permiso_mascotas' => ['required_if:tipo_vivienda,alquilada', 'nullable', 'boolean'],
            'experiencia_previa' => ['nullable', 'string', 'max:1000'],
            'acepta_compromiso' => ['accepted'],
            'telefono' => ['required', 'regex:/^[0-9+\s\-]{7,20}$/'],
        ];
    }

    /** Datos listos para guardar; con vivienda propia el permiso "no aplica" (null). */
    public function datosSolicitud(): array
    {
        $d = $this->validated();

        return [
            'numero_personas' => (int) $d['numero_personas'],
            'tipo_vivienda' => $d['tipo_vivienda'],
            'tiene_patio' => $this->boolean('tiene_patio'),
            'permiso_mascotas' => $d['tipo_vivienda'] === 'alquilada' ? $this->boolean('permiso_mascotas') : null,
            'experiencia_previa' => $d['experiencia_previa'] ?? null,
            'acepta_compromiso' => true,
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'required_if' => 'Indica si el propietario permite mascotas.',
            'in' => 'Selecciona una opción válida en :attribute.',
            'integer' => 'El campo :attribute debe ser un número entero.',
            'boolean' => 'Selecciona una opción válida en :attribute.',
            'min.numeric' => 'El campo :attribute debe ser al menos :min.',
            'max.numeric' => 'El campo :attribute no puede ser mayor que :max.',
            'max.string' => 'El campo :attribute no debe superar :max caracteres.',
            'accepted' => 'Debes aceptar el compromiso de tenencia responsable.',
            'telefono.required' => 'Escribe un teléfono de contacto.',
            'telefono.regex' => 'Ingresa un teléfono válido (solo números, entre 7 y 20 caracteres).',
        ];
    }

    public function attributes(): array
    {
        return [
            'numero_personas' => 'número de personas',
            'tipo_vivienda' => 'tipo de vivienda',
            'tiene_patio' => 'patio o jardín',
            'permiso_mascotas' => 'permiso del propietario',
            'experiencia_previa' => 'experiencia previa',
        ];
    }
}