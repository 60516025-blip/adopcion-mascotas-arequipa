@extends('layouts.app')

@section('titulo', 'Nueva contraseña')

@section('contenido')
<div class="container py-5">
    <div class="tarjeta-hu p-4 mx-auto" style="max-width:480px">
        <h1 class="h4 fw-bold">Crea tu nueva contraseña</h1>
        <p class="text-muted small">Usa al menos 8 caracteres.</p>

        <form method="POST" action="{{ route('password.update') }}" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required
                       autocomplete="email" class="form-control @error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Nueva contraseña</label>
                <input type="password" id="password" name="password" required autofocus autocomplete="new-password"
                       class="form-control @error('password') is-invalid @enderror">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       autocomplete="new-password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">Guardar contraseña</button>
        </form>
    </div>
</div>
@endsection