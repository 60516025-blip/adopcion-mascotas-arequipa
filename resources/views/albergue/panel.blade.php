@extends('layouts.panel')

@section('titulo', 'Mis mascotas')

@section('contenido')
@php
    // Siguiente estado con un clic: estado actual => [nuevo estado, texto del botón]
    $siguiente = [
        'disponible' => ['en_proceso', 'En proceso'],
        'en_proceso' => ['adoptada', 'Adoptada'],
    ];
    $claseEstado = ['disponible' => 'badge-ok', 'en_proceso' => 'badge-pend', 'adoptada' => 'badge-neutro'];
@endphp

<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <div>
        <h1 class="h3 fw-bold mb-1">Mis mascotas</h1>
        <p class="text-muted mb-0">Publica, edita y cambia el estado de tus mascotas con un clic.</p>
    </div>
    @if ($albergue->validado)
        <a href="{{ route('albergue.mascotas.create') }}" class="btn btn-primary">+ Agregar mascota</a>
    @endif
</div>

@unless ($albergue->validado)
    <div class="alert alert-warning" role="alert">
        Tu albergue está pendiente de validación. Podrás publicar mascotas cuando el administrador lo apruebe.
    </div>
@endunless

<div class="tarjeta-hu table-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Mascota</th>
                <th scope="col">Especie</th>
                <th scope="col">Estado</th>
                <th scope="col">Ficha sanitaria</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($mascotas as $mascota)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @if ($mascota->foto_url)
                            <img src="{{ asset($mascota->foto_url) }}" alt="Foto de {{ $mascota->nombre }}" class="miniatura">
                        @else
                            <span class="miniatura" aria-hidden="true"></span>
                        @endif
                        <strong>{{ $mascota->nombre }}</strong>
                    </div>
                </td>
                <td>{{ ucfirst($mascota->especie) }}</td>
                <td><span class="badge-hu {{ $claseEstado[$mascota->estado] }}">{{ $mascota->estado_texto }}</span></td>
                <td>
                    <span class="badge-hu {{ $mascota->ficha_completa ? 'badge-ok' : 'badge-pend' }}">
                        {{ $mascota->ficha_completa ? 'Completa' : 'Pendiente' }}
                    </span>
                </td>
                <td>
                    <div class="d-flex flex-wrap gap-1">
                        <a href="{{ route('albergue.mascotas.edit', $mascota) }}" class="btn btn-outline-primary btn-sm">Editar</a>

                        @if (isset($siguiente[$mascota->estado]))
                            <form method="POST" action="{{ route('albergue.mascotas.estado', $mascota) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="estado" value="{{ $siguiente[$mascota->estado][0] }}">
                                <button type="submit" class="btn btn-outline-primary btn-sm">{{ $siguiente[$mascota->estado][1] }}</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('albergue.mascotas.destroy', $mascota) }}"
                              onsubmit="return confirm('¿Eliminar esta mascota? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Aún no has publicado mascotas.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $mascotas->links('pagination::bootstrap-5') }}
</div>
@endsection