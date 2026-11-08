<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida el formulario de registro (adoptante o albergue/rescatista).
 */
class RegistroRequest extends FormRequest
{
    protected $errorBag = 'registro';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            // El administrador NO se puede elegir desde el formulario
            'tipo_cuenta' => ['required', 'in:adoptante,albergue'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'Ingresa un correo electrónico válido.',
            'unique' => 'Este :attribute ya está registrado.',
            'confirmed' => 'Las contraseñas no coinciden.',
            'min' => 'La :attribute debe tener al menos :min caracteres.',
            'max' => 'El campo :attribute no debe superar :max caracteres.',
            'in' => 'Selecciona un tipo de cuenta válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre completo',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'tipo_cuenta' => 'tipo de cuenta',
        ];
    }
}