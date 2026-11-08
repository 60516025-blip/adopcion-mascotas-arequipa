<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida los datos del formulario de inicio de sesión.
 */
class LoginRequest extends FormRequest
{
    // Los errores se guardan en la bolsa "login" porque la página tiene dos formularios
    protected $errorBag = 'login';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'Ingresa un correo electrónico válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'correo electrónico',
            'password' => 'contraseña',
        ];
    }
}