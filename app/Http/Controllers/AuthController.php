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
    /** Pantalla de inicio de sesión (Fig. 11). */
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    /** Pantalla de registro (Fig. 11). */
    public function mostrarRegistro()
    {
        return view('auth.registro');
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

        return redirect()->intended($this->inicioSegunRol($request->user()));
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

        return redirect($this->inicioSegunRol($usuario))->with('exito', $mensaje);
    }

    /** Cierra la sesión. */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /** Página de inicio según el rol del usuario. */
    private function inicioSegunRol(User $usuario): string
    {
        return match (true) {
            $usuario->esAdmin() => route('admin.dashboard'),
            $usuario->esAlbergue() => route('albergue.panel'),
            default => '/',
        };
    }
}