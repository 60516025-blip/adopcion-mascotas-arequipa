@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
<div class="container py-5">
    <h1 class="fw-bold">Mascotas rescatadas en Arequipa</h1>
    <p class="text-muted">Encuentra a tu compañero entre los albergues y rescatistas de Arequipa.</p>
    @auth
        <p>Sesión iniciada como <strong>{{ auth()->user()->name }}</strong> (rol: {{ auth()->user()->rol }}).</p>
    @endauth
</div>
@endsection