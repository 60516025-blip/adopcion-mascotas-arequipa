<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistroRequest;
use App\Models\Albergue;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador delgado de autenticación: valida con Form Requests
 * y delega la persistencia a Eloquent.
 */
class AuthController extends Controller
{
    /** Muestra login y registro en una sola página (Fig. 11). */
    public function mostrarAcceso()
    {
        return view('auth.acceso');
    }

    /** Inicia sesión con correo y contraseña. */
    public function login(LoginRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            // Mensaje genérico: no revela si el correo existe (seguridad)
            return back()
                ->withErrors(['email' => 'Correo o contraseña incorrectos.'], 'login')
                ->onlyInput('email');
        }

        // Evita la fijación de sesión
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    /** Registra un adoptante o un albergue (este último queda sin validar). */
    public function registrar(RegistroRequest $request): RedirectResponse
    {
        $datos = $request->validated();

        $usuario = DB::transaction(function () use ($datos) {
            $usuario = User::create([
                'name' => $datos['name'],
                'email' => $datos['email'],
                'password' => $datos['password'], // se cifra con el cast "hashed"
                'rol' => $datos['tipo_cuenta'] === 'albergue' ? 'albergue' : 'adoptante',
            ]);

            // El albergue debe ser validado por el admin antes de publicar
            if ($usuario->esAlbergue()) {
                Albergue::create([
                    'user_id' => $usuario->id,
                    'nombre' => $datos['name'],
                    'distrito' => 'Por definir',
                    'validado' => false,
                ]);
            }

            return $usuario;
        });

        Auth::login($usuario);
        $request->session()->regenerate();

        $mensaje = $usuario->esAlbergue()
            ? 'Cuenta creada. Un administrador debe validar tu albergue antes de publicar mascotas.'
            : 'Cuenta creada. ¡Bienvenido a Huellitas Arequipa!';

        return redirect('/')->with('exito', $mensaje);
    }

    /** Cierra la sesión. */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}