@extends('layouts.app')

@section('titulo', 'Crear cuenta')

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
                <h2 class="h3 fw-bold">Únete a Huellitas Arequipa</h2>
                <p class="mb-2"><strong>Adoptantes:</strong> encuentra a tu compañero y sigue tu solicitud.</p>
                <p class="mb-0"><strong>Albergues y rescatistas:</strong> publica y gestiona tus mascotas (tu cuenta requiere validación).</p>
            </div>
            <div class="col-md-7 p-4 p-lg-5">
                <h1 class="h4 fw-bold mb-1">Crear cuenta</h1>
                <p class="text-muted small mb-4">Completa tus datos para registrarte.</p>

                <form method="POST" action="{{ route('registro.enviar') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="reg_name" class="form-label">Nombre completo</label>
                        <input type="text" id="reg_name" name="name" autocomplete="name" required autofocus
                               placeholder="Ejemplo: Juan Pérez" value="{{ old('name') }}"
                               class="form-control @error('name', 'registro') is-invalid @enderror">
                        @error('name', 'registro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="reg_email" class="form-label">Correo electrónico</label>
                        <input type="email" id="reg_email" name="email" autocomplete="email" required
                               placeholder="nombre@correo.com" value="{{ old('email') }}"
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
                        @php $tipo = old('tipo_cuenta', 'adoptante'); @endphp
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

                <p class="text-center small mt-4 mb-0">
                    ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection