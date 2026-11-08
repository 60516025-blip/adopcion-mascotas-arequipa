<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida los filtros del catálogo (solo se aceptan valores conocidos).
 */
class CatalogoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'especie' => ['nullable', 'array'],
            'especie.*' => ['in:perro,gato'],
            'tamano' => ['nullable', 'array'],
            'tamano.*' => ['in:pequeno,mediano,grande'],
            'edad' => ['nullable', 'array'],
            'edad.*' => ['in:cachorro,adulto,senior'],
            'salud' => ['nullable', 'array'],
            'salud.*' => ['in:vacunada,desparasitada,esterilizada'],
        ];
    }
}