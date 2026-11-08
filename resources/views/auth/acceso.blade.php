@extends('layouts.app')

@section('titulo', 'Acceso')

@section('contenido')
<div class="container py-5">
    <div class="row g-4 align-items-start">

        {{-- Iniciar sesión --}}
        <div class="col-lg-6">
            <div class="tarjeta-hu p-4">
                <h1 class="h4 fw-bold">Iniciar sesión</h1>
                <p class="text-muted small">Ingresa con tu cuenta para solicitar una adopción o gestionar tus mascotas.</p>
                <form method="POST" action="{{ route('login.enviar') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="login_email" class="form-label">Correo electrónico</label>
                        <input type="email" id="login_email" name="email" autocomplete="email" required
                               placeholder="nombre@correo.com"
                               value="{{ $errors->login->any() ? old('email') : '' }}"
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
            </div>
        </div>

        {{-- Crear cuenta --}}
        <div class="col-lg-6">
            <div class="tarjeta-hu p-4">
                <h2 class="h4 fw-bold">Crear cuenta</h2>
                <p class="text-muted small">Regístrate como adoptante o como albergue/rescatista (requiere validación).</p>
                <form method="POST" action="{{ route('registro.enviar') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="reg_name" class="form-label">Nombre completo</label>
                        <input type="text" id="reg_name" name="name" autocomplete="name" required
                               placeholder="Ejemplo: Juan Pérez"
                               value="{{ $errors->registro->any() ? old('name') : '' }}"
                               class="form-control @error('name', 'registro') is-invalid @enderror">
                        @error('name', 'registro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="reg_email" class="form-label">Correo electrónico</label>
                        <input type="email" id="reg_email" name="email" autocomplete="email" required
                               placeholder="nombre@correo.com"
                               value="{{ $errors->registro->any() ? old('email') : '' }}"
                               class="form-control @error('email', 'registro') is-invalid @enderror">
                        @error('email', 'registro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="reg_password" class="form-label">Contraseña</label>
                            <input type="password" id="reg_password" name="password" autocomplete="new-password" required
                                   class="form-control @error('password', 'registro') is-invalid @enderror">
                            @error('password', 'registro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="reg_password_confirmation" class="form-label">Confirmar contraseña</label>
                            <input type="password" id="reg_password_confirmation" name="password_confirmation"
                                   autocomplete="new-password" required class="form-control">
                        </div>
                    </div>
                    <fieldset class="mb-3">
                        <legend class="form-label fs-6">Tipo de cuenta</legend>
                        @php $tipo = $errors->registro->any() ? old('tipo_cuenta', 'adoptante') : 'adoptante'; @endphp
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo_cuenta" id="tipo_adoptante"
                                   value="adoptante" {{ $tipo === 'adoptante' ? 'checked' : '' }}>
                            <label class="form-check-label" for="tipo_adoptante">Adoptante</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo_cuenta" id="tipo_albergue"
                                   value="albergue" {{ $tipo === 'albergue' ? 'checked' : '' }}>
                            <label class="form-check-label" for="tipo_albergue">Albergue o rescatista</label>
                        </div>
                        @error('tipo_cuenta', 'registro')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </fieldset>
                    <button type="submit" class="btn btn-primary w-100">Crear cuenta</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection