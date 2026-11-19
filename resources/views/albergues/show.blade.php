@extends('layouts.app')

@section('titulo', $albergue->nombre)

@section('contenido')
<div class="container py-4">
    <a href="{{ route('albergues.index') }}" class="small">&larr; Volver a albergues</a>

    <div class="tarjeta-hu p-4 mt-2 mb-4">
        <h1 class="h3 fw-bold mb-1">{{ $albergue->nombre }}</h1>
        <p class="text-muted mb-2">{{ $albergue->distrito }} · Arequipa</p>
        <p class="mb-0">{{ $albergue->descripcion ?: 'Este albergue aún no agregó una descripción.' }}</p>
    </div>

    <h2 class="h4 fw-bold mb-3">Mascotas disponibles ({{ $mascotas->total() }})</h2>

    @if ($mascotas->isEmpty())
        <div class="tarjeta-hu p-4 text-center text-muted">
            Por ahora no hay mascotas disponibles en este albergue.
            <a href="{{ route('catalogo') }}">Ver el catálogo completo</a>
        </div>
    @else
        <div class="row g-3">
            @foreach ($mascotas as $mascota)
                <div class="col-md-6 col-xl-4">
                    <div class="tarjeta-hu card-mascota h-100 d-flex flex-column">
                        @if ($mascota->foto_url)
                            <img src="{{ asset($mascota->foto_url) }}" alt="Foto de {{ $mascota->nombre }}"
                                 class="w-100 rounded-top object-fit-cover" style="height:180px">
                        @else
                            <div class="foto-placeholder w-100 rounded-top" style="height:180px">Foto de {{ $mascota->nombre }}</div>
                        @endif
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <h3 class="h6 fw-bold mb-1">{{ $mascota->nombre }}</h3>
                            <p class="small text-muted mb-2">
                                {{ ucfirst($mascota->especie) }} · {{ $mascota->tamano_texto }} · {{ $mascota->edad_texto }}
                            </p>
                            <div class="mb-3 d-flex flex-wrap gap-1">
                                <span class="badge-hu badge-ok">{{ $mascota->estado_texto }}</span>
                                @if ($mascota->esta_vacunada)
                                    <span class="badge-hu badge-neutro">Vacunada</span>
                                @endif
                            </div>
                            <a href="{{ route('mascotas.show', $mascota->id) }}" class="btn btn-outline-primary btn-sm w-100 mt-auto">Ver ficha</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $mascotas->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection