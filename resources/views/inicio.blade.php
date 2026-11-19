@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
@php $portada = file_exists(public_path('img/portada.jpg')); @endphp

{{-- Portada --}}
<section class="hero-hu {{ $portada ? 'hero-foto' : '' }}">
    <svg class="hero-pata" viewBox="0 0 64 64" aria-hidden="true" focusable="false">
        <ellipse cx="13" cy="28" rx="6" ry="8" transform="rotate(-20 13 28)"/>
        <ellipse cx="25" cy="15" rx="6" ry="8" transform="rotate(-8 25 15)"/>
        <ellipse cx="39" cy="15" rx="6" ry="8" transform="rotate(8 39 15)"/>
        <ellipse cx="51" cy="28" rx="6" ry="8" transform="rotate(20 51 28)"/>
        <path d="M32 33c-9 0-17 8-17 15 0 5 4 7 9 7 3 0 5-1 8-1s5 1 8 1c5 0 9-2 9-7 0-7-8-15-17-15z"/>
    </svg>
    <div class="container position-relative py-5">
        <div class="row py-lg-4">
            <div class="col-lg-7">
                <p class="etiqueta-hero mb-2">Adopción responsable en Arequipa</p>
                <h1 class="display-4 fw-bold mb-3">Dale un hogar. Cambia dos vidas.</h1>
                <p class="lead mb-4">
                    Conectamos a albergues y rescatistas de Arequipa con familias que quieren adoptar.
                    Conoce a las mascotas, revisa su salud y envía tu solicitud en pocos pasos.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('catalogo') }}" class="btn btn-claro btn-lg">Ver mascotas en adopción</a>
                    @guest
                        <a href="{{ route('registro') }}" class="btn btn-borde-claro btn-lg">Crear mi cuenta</a>
                    @else
                        <a href="#como-funciona" class="btn btn-borde-claro btn-lg">Cómo funciona</a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Cifras reales --}}
<section class="bg-white border-bottom">
    <div class="container py-4">
        <div class="row text-center g-3">
            <div class="col-4 stat-hu">
                <div class="num">{{ $stats['disponibles'] }}</div>
                <div class="text-muted small">Mascotas esperando un hogar</div>
            </div>
            <div class="col-4 stat-hu">
                <div class="num">{{ $stats['albergues'] }}</div>
                <div class="text-muted small">Albergues y rescatistas validados</div>
            </div>
            <div class="col-4 stat-hu">
                <div class="num">{{ $stats['adopciones'] }}</div>
                <div class="text-muted small">Adopciones concretadas</div>
            </div>
        </div>
    </div>
</section>

{{-- Cómo funciona --}}
<section id="como-funciona" class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Adoptar es más simple de lo que crees</h2>
            <p class="text-muted mb-0">Un proceso corto y ordenado, pensado para que cada mascota llegue al hogar correcto.</p>
        </div>
        <div class="row g-4 text-center">
            @foreach ([
                ['1', 'Explora', 'Busca por especie, tamaño, edad o salud y revisa la ficha sanitaria de cada mascota.'],
                ['2', 'Solicita', 'Completa la preadopción en 4 pasos: tus datos, tu vivienda, tu experiencia y el compromiso de tenencia responsable.'],
                ['3', 'Conoce a tu compañero', 'El albergue revisa tu solicitud y, si la aprueba, coordina contigo la entrega.'],
            ] as [$numero, $titulo, $texto])
                <div class="col-md-4">
                    <div class="tarjeta-hu p-4 h-100">
                        <div class="paso-num">{{ $numero }}</div>
                        <h3 class="h5 fw-bold">{{ $titulo }}</h3>
                        <p class="text-muted mb-0">{{ $texto }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Mascotas destacadas --}}
@if ($destacadas->isNotEmpty())
<section class="pb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
            <div>
                <h2 class="fw-bold mb-1">Ellos te están esperando</h2>
                <p class="text-muted mb-0">Algunas de las últimas mascotas publicadas.</p>
            </div>
            <a href="{{ route('catalogo') }}" class="btn btn-outline-primary">Ver todas</a>
        </div>
        <div class="row g-3">
            @foreach ($destacadas as $mascota)
                <div class="col-md-6 col-lg-4">
                    <div class="tarjeta-hu card-mascota h-100 d-flex flex-column">
                        @if ($mascota->foto_url)
                            <img src="{{ asset($mascota->foto_url) }}" alt="Foto de {{ $mascota->nombre }}"
                                 class="w-100 rounded-top object-fit-cover" style="height:180px">
                        @else
                            <div class="foto-placeholder w-100 rounded-top" style="height:180px">Foto de {{ $mascota->nombre }}</div>
                        @endif
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <h3 class="h6 fw-bold mb-1">{{ $mascota->nombre }}</h3>
                            <p class="small text-muted mb-1">
                                {{ ucfirst($mascota->especie) }} · {{ $mascota->tamano_texto }} · {{ $mascota->edad_texto }}
                            </p>
                            <p class="small text-muted mb-2">{{ $mascota->albergue->nombre }} · {{ $mascota->albergue->distrito }}</p>
                            <div class="mb-3 d-flex flex-wrap gap-1">
                                <span class="badge-hu badge-ok">{{ $mascota->estado_texto }}</span>
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
    </div>
</section>
@endif

{{-- Para albergues --}}
<section class="banda-albergue py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h2 class="fw-bold">¿Eres albergue o rescatista?</h2>
                <p>Publica a tus mascotas en un catálogo siempre visible y recibe solo solicitudes de personas que ya completaron la preevaluación.</p>
                <ul class="lista-check">
                    <li>Cambia el estado de cada mascota con un clic.</li>
                    <li>Muestra su ficha sanitaria: vacunas, desparasitación y esterilización.</li>
                    <li>Revisa, aprueba o rechaza solicitudes con un mensaje para el adoptante.</li>
                </ul>
            </div>
            <div class="col-lg-4 text-lg-end">
                @guest
                    <a href="{{ route('registro') }}" class="btn btn-claro btn-lg">Registrar mi albergue</a>
                @else
                    @if (auth()->user()->esAlbergue())
                        <a href="{{ route('albergue.panel') }}" class="btn btn-claro btn-lg">Ir a mi panel</a>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</section>
@endsection