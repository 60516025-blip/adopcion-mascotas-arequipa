@extends('layouts.panel')

@section('titulo', 'Solicitudes recibidas')

@section('contenido')
@php
    $filtros = ['' => 'Todas', 'pendiente' => 'Pendientes', 'aprobada' => 'Aprobadas', 'rechazada' => 'Rechazadas'];
    $claseEstado = ['pendiente' => 'badge-pend', 'aprobada' => 'badge-ok', 'rechazada' => 'badge-neutro'];
@endphp

<h1 class="h3 fw-bold mb-1">Solicitudes recibidas</h1>
<p class="text-muted">Revisa la preevaluación de cada adoptante y aprueba o rechaza con una respuesta.</p>

<ul class="nav nav-pills mb-3">
    @foreach ($filtros as $valor => $texto)
        <li class="nav-item">
            <a class="nav-link {{ (string) $estado === (string) $valor ? 'active' : '' }}"
               href="{{ route('albergue.solicitudes.index', $valor === '' ? [] : ['estado' => $valor]) }}">{{ $texto }}</a>
        </li>
    @endforeach
</ul>

<div class="tarjeta-hu table-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Adoptante</th>
                <th scope="col">Mascota</th>
                <th scope="col">Preevaluación</th>
                <th scope="col">Fecha</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($solicitudes as $solicitud)
            <tr>
                <td><strong>{{ $solicitud->adoptante->name }}</strong></td>
                <td>{{ $solicitud->mascota->nombre }}</td>
                <td>
                    <span class="badge-hu {{ $solicitud->preevaluacion === 'Apto' ? 'badge-ok' : 'badge-pend' }}">
                        {{ $solicitud->preevaluacion }}
                    </span>
                </td>
                <td>{{ $solicitud->created_at?->format('d/m/Y') }}</td>
                <td><span class="badge-hu {{ $claseEstado[$solicitud->estado] ?? 'badge-neutro' }}">{{ ucfirst($solicitud->estado) }}</span></td>
                <td>
                    <a href="{{ route('albergue.solicitudes.show', $solicitud) }}" class="btn btn-outline-primary btn-sm">Ver detalle</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No hay solicitudes en esta lista.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $solicitudes->links('pagination::bootstrap-5') }}
</div>
@endsection