<?php

namespace App\Http\Controllers\Albergue;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Perfil del albergue (RF02): nombre, distrito y descripción.
 */
class PerfilController extends Controller
{
    public function edit(Request $request)
    {
        return view('albergue.perfil.edit', ['albergue' => $request->user()->albergue]);
    }

    public function update(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'distrito' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'max' => 'El campo :attribute no debe superar :max caracteres.',
        ], [
            'nombre' => 'nombre',
            'distrito' => 'distrito',
            'descripcion' => 'descripción',
        ]);

        // forceFill es seguro aquí: $datos solo trae los tres campos validados
        $request->user()->albergue->forceFill($datos)->save();

        return redirect()->route('albergue.perfil.edit')->with('exito', 'Perfil actualizado.');
    }
}