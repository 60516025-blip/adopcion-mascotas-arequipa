<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * Recuperación de contraseña con enlace de un solo uso que caduca
 * (usa el broker de Laravel y la tabla password_reset_tokens).
 */
class PasswordResetController extends Controller
{
    public function mostrarSolicitud()
    {
        return view('auth.olvide');
    }

    public function enviarEnlace(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'Escribe tu correo electrónico.',
            'email.email' => 'Ingresa un correo electrónico válido.',
        ]);

        Password::sendResetLink($request->only('email'));

        // Mensaje genérico: no revela si el correo está registrado (seguridad)
        return back()->with('exito', 'Si el correo está registrado, te enviamos un enlace para restablecer tu contraseña.');
    }

    public function mostrarRestablecer(Request $request, string $token)
    {
        return view('auth.restablecer', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function restablecer(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'password.required' => 'Escribe tu nueva contraseña.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
        ]);

        $estado = Password::reset($datos, function (User $usuario, string $password) {
            $usuario->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($usuario));
        });

        if ($estado === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('exito', 'Contraseña actualizada. Ya puedes iniciar sesión.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'El enlace no es válido o ya venció. Solicita uno nuevo.']);
    }
}