@extends('layouts.app')

@section('titulo', 'Catálogo')

@section('contenido')
@php
    // Grupos de filtros: campo => [título, [valor => etiqueta]]
    $grupos = [
        'especie' => ['Especie', ['perro' => 'Perro', 'gato' => 'Gato']],
        'tamano' => ['Tamaño', ['pequeno' => 'Pequeño', 'mediano' => 'Mediano', 'grande' => 'Grande']],
        'edad' => ['Edad', ['cachorro' => 'Cachorro', 'adulto' => 'Adulto', 'senior' => 'Senior']],
        'salud' => ['Salud', ['vacunada' => 'Vacunada', 'desparasitada' => 'Desparasitada', 'esterilizada' => 'Esterilizada']],
    ];
@endphp

<div class="container py-4">
    <h1 class="fw-bold">Mascotas en adopción</h1>
    <p class="text-muted">Encuentra a tu compañero entre los albergues y rescatistas de Arequipa.</p>

    <form method="GET" action="{{ route('catalogo') }}">
        <div class="row g-4">

            {{-- Filtros --}}
            <aside class="col-lg-3">
                <div class="tarjeta-hu p-3">
                    <h2 class="h6 fw-bold mb-3">Filtros</h2>

                    @foreach ($grupos as $campo => [$titulo, $opciones])
                        <fieldset class="mb-3">
                            <legend class="fs-6 fw-semibold">{{ $titulo }}</legend>
                            @foreach ($opciones as $valor => $etiqueta)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="{{ $campo }}[]" value="{{ $valor }}"
                                           id="{{ $campo }}_{{ $valor }}"
                                           @checked(in_array($valor, $filtros[$campo] ?? [], true))>
                                    <label class="form-check-label" for="{{ $campo }}_{{ $valor }}">{{ $etiqueta }}</label>
                                </div>
                            @endforeach
                        </fieldset>
                    @endforeach

                    <button type="submit" class="btn btn-primary w-100 mb-2">Aplicar filtros</button>
                    <a href="{{ route('catalogo') }}" class="btn btn-outline-primary w-100">Limpiar</a>
                </div>
            </aside>

            {{-- Resultados --}}
            <section class="col-lg-9">
                <div class="input-group mb-3">
                    <label for="q" class="visually-hidden">Buscar por nombre o raza</label>
                    <input type="search" id="q" name="q" class="form-control"
                           placeholder="Buscar por nombre o raza" maxlength="100"
                           value="{{ $filtros['q'] ?? '' }}">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>

                <p class="small text-muted">
                    {{ $mascotas->total() }} {{ $mascotas->total() === 1 ? 'mascota encontrada' : 'mascotas encontradas' }}
                </p>

                @if ($mascotas->isEmpty())
                    <div class="tarjeta-hu p-4 text-center text-muted">
                        No hay mascotas con esos filtros. Prueba quitando algunos.
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
                                        <div class="foto-placeholder w-100 rounded-top" style="height:180px">
                                            Foto de {{ $mascota->nombre }}
                                        </div>
                                    @endif

                                    <div class="p-3 d-flex flex-column flex-grow-1">
                                        <h3 class="h6 fw-bold mb-1">{{ $mascota->nombre }}</h3>
                                        <p class="small text-muted mb-2">
                                            {{ ucfirst($mascota->especie) }} · {{ $mascota->tamano_texto }} · {{ $mascota->edad_texto }}
                                        </p>
                                        <div class="mb-3 d-flex flex-wrap gap-1">
                                            <span class="badge-hu {{ $mascota->estado === 'disponible' ? 'badge-ok' : 'badge-pend' }}">
                                                {{ $mascota->estado_texto }}
                                            </span>
                                            @if ($mascota->esta_vacunada)
                                                <span class="badge-hu badge-neutro">Vacunada</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('mascotas.show', $mascota->id) }}"
                                           class="btn btn-outline-primary btn-sm w-100 mt-auto">Ver ficha</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $mascotas->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </section>

        </div>
    </form>
</div>
@endsection