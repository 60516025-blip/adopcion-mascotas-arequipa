@extends('layouts.app')

@section('titulo', $mascota->nombre)

@section('contenido')
@php $ficha = $mascota->fichaMedica; @endphp

<div class="container py-4">
    <nav aria-label="breadcrumb" class="small mb-3">
        <a href="{{ route('catalogo') }}" class="text-decoration-none">Catálogo</a>
        <span class="text-muted">/ {{ $mascota->nombre }}</span>
    </nav>

    <div class="row g-4">
        {{-- Foto --}}
        <div class="col-lg-6">
            @if ($mascota->foto_url)
                <img src="{{ asset($mascota->foto_url) }}" alt="Foto de {{ $mascota->nombre }}"
                     class="w-100 rounded object-fit-cover" style="height:380px">
            @else
                <div class="foto-placeholder w-100 rounded" style="height:380px">
                    Foto principal de {{ $mascota->nombre }}
                </div>
            @endif
        </div>

        {{-- Datos --}}
        <div class="col-lg-6">
            <h1 class="fw-bold d-inline me-2">{{ $mascota->nombre }}</h1>
            <span class="badge-hu {{ $mascota->estado === 'disponible' ? 'badge-ok' : 'badge-pend' }}">
                {{ $mascota->estado_texto }}
            </span>

            <div class="row g-2 my-3">
                @foreach ([
                    'Especie' => ucfirst($mascota->especie),
                    'Tamaño' => $mascota->tamano_texto,
                    'Edad' => $mascota->edad_texto,
                    'Sexo' => ucfirst($mascota->genero),
                ] as $titulo => $valor)
                    <div class="col-6 col-md-3">
                        <div class="tarjeta-hu p-2">
                            <div class="small text-muted">{{ $titulo }}</div>
                            <div class="fw-semibold">{{ $valor }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <p>{{ $mascota->descripcion }}</p>

            {{-- Ficha sanitaria --}}
            <div class="tarjeta-hu p-3 mb-3">
                <h2 class="h6 fw-bold">Ficha sanitaria</h2>
                @php
                    $vacunacion = $ficha && $ficha->vacuna_antirrabica && $ficha->vacuna_quintuple;
                @endphp
                <div class="d-flex justify-content-between py-1">
                    <span>Vacunación</span>
                    <span class="badge-hu {{ $vacunacion ? 'badge-ok' : 'badge-pend' }}">{{ $vacunacion ? 'Completa' : 'Incompleta' }}</span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Desparasitación</span>
                    <span class="badge-hu {{ $ficha?->desparasitado ? 'badge-ok' : 'badge-pend' }}">{{ $ficha?->desparasitado ? 'Al día' : 'Pendiente' }}</span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Esterilización</span>
                    <span class="badge-hu {{ $ficha?->esterilizado ? 'badge-ok' : 'badge-pend' }}">{{ $ficha?->esterilizado ? 'Realizada' : 'Pendiente' }}</span>
                </div>
            </div>

            {{-- Albergue --}}
            <div class="tarjeta-hu p-3 mb-3">
                <div class="fw-semibold">{{ $mascota->albergue->nombre }}</div>
                <div class="small text-muted">{{ $mascota->albergue->distrito }} · Arequipa</div>
            </div>

            {{-- Solicitud --}}
            @if ($mascota->estado === 'disponible')
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">Iniciar sesión para adoptar</a>
                @else
                    @if (auth()->user()->esAdoptante())
                        {{-- El formulario de preadopción se conecta en el siguiente paso --}}
                        <a href="#" class="btn btn-primary btn-lg w-100">Iniciar solicitud de adopción</a>
                    @endif
                @endguest
                <p class="small text-muted mt-2">
                    Para solicitar la adopción debes iniciar sesión como adoptante y completar la preevaluación.
                </p>
            @else
                <div class="alert alert-warning mb-0">Esta mascota ya tiene una adopción en proceso.</div>
            @endif
        </div>
    </div>
</div>
@endsection