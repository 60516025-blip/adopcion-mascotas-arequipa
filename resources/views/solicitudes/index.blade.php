@extends('layouts.app')

@section('titulo', 'Mis solicitudes')

@section('contenido')
@php
    $claseEstado = ['pendiente' => 'badge-pend', 'aprobada' => 'badge-ok', 'rechazada' => 'badge-neutro'];
    $textoEstado = ['pendiente' => 'En revisión', 'aprobada' => 'Aprobada', 'rechazada' => 'Rechazada'];
@endphp

<div class="container py-4">
    <h1 class="fw-bold mb-1">Mis solicitudes</h1>
    <p class="text-muted mb-4">Sigue el estado de tus solicitudes de adopción.</p>

    <div class="tarjeta-hu table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col">Mascota</th>
                    <th scope="col">Albergue</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Respuesta del albergue</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($solicitudes as $solicitud)
                <tr>
                    <td><a href="{{ route('mascotas.show', $solicitud->mascota->id) }}"><strong>{{ $solicitud->mascota->nombre }}</strong></a></td>
                    <td>{{ $solicitud->mascota->albergue->nombre }}</td>
                    <td>{{ $solicitud->created_at?->format('d/m/Y') }}</td>
                    <td><span class="badge-hu {{ $claseEstado[$solicitud->estado] ?? 'badge-neutro' }}">{{ $textoEstado[$solicitud->estado] ?? ucfirst($solicitud->estado) }}</span></td>
                    <td>
                        @if ($solicitud->respuesta)
                            {{ $solicitud->respuesta }}
                        @elseif ($solicitud->estado === 'aprobada')
                            El albergue se pondrá en contacto contigo para coordinar la entrega.
                        @else
                            <span class="text-muted">Aún sin respuesta</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Aún no has enviado solicitudes.
                        <a href="{{ route('catalogo') }}">Ver mascotas en adopción</a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $solicitudes->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection