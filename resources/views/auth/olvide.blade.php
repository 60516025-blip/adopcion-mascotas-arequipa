@extends('layouts.app')

@section('titulo', 'Recuperar contraseña')

@section('contenido')
<div class="container py-5">
    <div class="tarjeta-hu p-4 mx-auto" style="max-width:480px">
        <h1 class="h4 fw-bold">¿Olvidaste tu contraseña?</h1>
        <p class="text-muted small">Escribe tu correo y te enviaremos un enlace para crear una nueva.</p>

        <form method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       autocomplete="email" placeholder="nombre@correo.com"
                       class="form-control @error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar enlace</button>
        </form>

        <p class="text-center small mt-4 mb-0">
            <a href="{{ route('login') }}" class="text-decoration-none">Volver a iniciar sesión</a>
        </p>
    </div>
</div>
@endsection