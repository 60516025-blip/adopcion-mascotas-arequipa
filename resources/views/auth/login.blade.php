@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

@section('contenido')
<div class="container py-5">
    <div class="tarjeta-hu acceso-card overflow-hidden mx-auto">
        <div class="row g-0">
            <div class="col-md-5 panel-acceso d-flex flex-column justify-content-center p-4 p-lg-5">
                <svg class="panel-pata" viewBox="0 0 64 64" aria-hidden="true" focusable="false">
                    <ellipse cx="13" cy="28" rx="6" ry="8" transform="rotate(-20 13 28)"/>
                    <ellipse cx="25" cy="15" rx="6" ry="8" transform="rotate(-8 25 15)"/>
                    <ellipse cx="39" cy="15" rx="6" ry="8" transform="rotate(8 39 15)"/>
                    <ellipse cx="51" cy="28" rx="6" ry="8" transform="rotate(20 51 28)"/>
                    <path d="M32 33c-9 0-17 8-17 15 0 5 4 7 9 7 3 0 5-1 8-1s5 1 8 1c5 0 9-2 9-7 0-7-8-15-17-15z"/>
                </svg>
                <h2 class="h3 fw-bold">Qué bueno verte de nuevo</h2>
                <p class="mb-0">Ingresa para solicitar una adopción o gestionar tus mascotas.</p>
            </div>
            <div class="col-md-7 p-4 p-lg-5">
                <h1 class="h4 fw-bold mb-1">Iniciar sesión</h1>
                <p class="text-muted small mb-4">Usa el correo con el que te registraste.</p>

                <form method="POST" action="{{ route('login.enviar') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="login_email" class="form-label">Correo electrónico</label>
                        <input type="email" id="login_email" name="email" autocomplete="email" required autofocus
                               placeholder="nombre@correo.com" value="{{ old('email') }}"
                               class="form-control @error('email', 'login') is-invalid @enderror">
                        @error('email', 'login')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="login_password" class="form-label">Contraseña</label>
                        <input type="password" id="login_password" name="password" autocomplete="current-password" required
                               class="form-control @error('password', 'login') is-invalid @enderror">
                        @error('password', 'login')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <a href="#" class="small text-decoration-none">¿Olvidaste tu contraseña?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                </form>

                <p class="text-center small mt-4 mb-0">
                    ¿Aún no tienes cuenta? <a href="{{ route('registro') }}" class="fw-semibold text-decoration-none">Crea una</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection