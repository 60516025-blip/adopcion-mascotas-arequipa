@extends('layouts.app')

@section('titulo', 'Cómo adoptar')

@section('contenido')
<section class="hero-hu">
    <div class="container position-relative py-5">
        <div class="col-lg-8">
            <p class="etiqueta-hero mb-2">Guía de adopción</p>
            <h1 class="display-5 fw-bold mb-3">Cómo adoptar en Huellitas Arequipa</h1>
            <p class="lead mb-0">Un proceso corto y ordenado, pensado para que cada mascota llegue al hogar correcto.</p>
        </div>
    </div>
</section>

<div class="container py-5">

    {{-- Pasos --}}
    <h2 class="fw-bold mb-4">El proceso paso a paso</h2>
    <div class="row g-4 mb-5">
        @foreach ([
            ['1', 'Explora el catálogo', 'Busca por especie, tamaño, edad o salud, y revisa la ficha sanitaria de cada mascota.'],
            ['2', 'Crea tu cuenta', 'Regístrate como adoptante. Es necesario para enviar una solicitud.'],
            ['3', 'Completa la preadopción', 'Son 4 pasos cortos: tus datos, tu vivienda, tu experiencia y el compromiso de tenencia responsable.'],
            ['4', 'El albergue revisa', 'El albergue ve tus respuestas y decide si aprueba o rechaza, con un mensaje para ti.'],
            ['5', 'Sigue tu solicitud', 'Mira el estado y la respuesta en «Mis solicitudes». Si te aprueban, el albergue coordina la entrega contigo.'],
        ] as [$numero, $titulo, $texto])
            <div class="col-md-6 col-lg-4">
                <div class="tarjeta-hu p-4 h-100">
                    <div class="paso-num">{{ $numero }}</div>
                    <h3 class="h5 fw-bold text-center">{{ $titulo }}</h3>
                    <p class="text-muted mb-0 text-center">{{ $texto }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Qué se pregunta y compromiso --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="tarjeta-hu p-4 h-100">
                <h2 class="h4 fw-bold">Qué te preguntamos</h2>
                <p class="text-muted">Estas respuestas ayudan al albergue a encontrar el mejor hogar:</p>
                <ul class="mb-0">
                    <li>Cuántas personas viven en tu hogar.</li>
                    <li>Si tu vivienda es propia o alquilada, y si el propietario permite mascotas.</li>
                    <li>Si cuentas con patio, jardín o terraza.</li>
                    <li>Tu experiencia previa con mascotas (opcional).</li>
                    <li>Que aceptes el compromiso de tenencia responsable.</li>
                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="tarjeta-hu p-4 h-100">
                <h2 class="h4 fw-bold">Compromiso de tenencia responsable</h2>
                <p class="text-muted">Al adoptar te comprometes a:</p>
                <ul class="mb-0">
                    <li>Brindar alimentación, agua, abrigo y atención veterinaria.</li>
                    <li>Mantener sus vacunas al día y considerar su esterilización.</li>
                    <li>No abandonarla: si ya no puedes cuidarla, avisar al albergue.</li>
                    <li>Respetar que la decisión final la toma el albergue.</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Preguntas frecuentes --}}
    <h2 class="fw-bold mb-3">Preguntas frecuentes</h2>
    <div class="accordion mb-5" id="faq">
        @foreach ([
            ['¿Cuesta enviar una solicitud?', 'La plataforma no cobra por solicitar una adopción. Cualquier condición adicional la define cada albergue y te la comunicará al coordinar la entrega.'],
            ['¿Cuánto demora la respuesta?', 'Depende de cada albergue. Puedes ver el estado de tu solicitud en «Mis solicitudes» cuando inicies sesión.'],
            ['¿Puedo solicitar varias mascotas?', 'Sí, pero solo puedes tener una solicitud pendiente por mascota. Las mascotas que ya están en proceso o adoptadas no reciben solicitudes.'],
            ['¿Quién ve mis datos?', 'El albergue ve las respuestas de tu preadopción. Tu correo y tu teléfono solo se le muestran cuando aprueba tu solicitud.'],
            ['¿Cómo registro mi albergue?', 'Crea una cuenta de tipo «Albergue o rescatista». Un administrador valida tu cuenta y, desde ese momento, puedes publicar mascotas desde tu panel.'],
        ] as $i => [$pregunta, $respuesta])
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}"
                            aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="faq{{ $i }}">
                        {{ $pregunta }}
                    </button>
                </h3>
                <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#faq">
                    <div class="accordion-body">{{ $respuesta }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center">
        <a href="{{ route('catalogo') }}" class="btn btn-primary btn-lg">Ver mascotas en adopción</a>
        @guest
            <a href="{{ route('registro') }}" class="btn btn-outline-primary btn-lg ms-2">Crear mi cuenta</a>
        @endguest
    </div>
</div>
@endsection