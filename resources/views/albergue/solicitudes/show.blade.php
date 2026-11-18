@extends('layouts.panel')

@section('titulo', 'Detalle de la solicitud')

@section('contenido')
@php
    $claseEstado = ['pendiente' => 'badge-pend', 'aprobada' => 'badge-ok', 'rechazada' => 'badge-neutro'];
    $adoptante = $solicitud->adoptante;
    $permiso = is_null($solicitud->permiso_mascotas) ? 'No aplica' : ($solicitud->permiso_mascotas ? 'Sí' : 'No');
@endphp

<a href="{{ route('albergue.solicitudes.index') }}" class="small">&larr; Volver a solicitudes</a>

<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mt-2 mb-3">
    <div>
        <h1 class="h3 fw-bold mb-1">Solicitud para {{ $solicitud->mascota->nombre }}</h1>
        <p class="text-muted mb-0">Enviada el {{ $solicitud->created_at?->format('d/m/Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        <span class="badge-hu {{ $solicitud->preevaluacion === 'Apto' ? 'badge-ok' : 'badge-pend' }}">Preevaluación: {{ $solicitud->preevaluacion }}</span>
        <span class="badge-hu {{ $claseEstado[$solicitud->estado] ?? 'badge-neutro' }}">{{ ucfirst($solicitud->estado) }}</span>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="tarjeta-hu p-4 h-100">
            <h2 class="h5 fw-bold mb-3">Adoptante</h2>
            <dl class="row mb-0">
                <dt class="col-sm-4">Nombre</dt><dd class="col-sm-8">{{ $adoptante->name }}</dd>
                <dt class="col-sm-4">Distrito</dt><dd class="col-sm-8">{{ $adoptante->distrito ?: 'No registrado' }}</dd>
                @if ($solicitud->estado === 'aprobada')
                    <dt class="col-sm-4">Correo</dt><dd class="col-sm-8">{{ $adoptante->email }}</dd>
                    <dt class="col-sm-4">Teléfono</dt><dd class="col-sm-8">{{ $adoptante->telefono ?: 'No registrado' }}</dd>
                @endif
            </dl>
            @if ($solicitud->estado !== 'aprobada')
                <p class="small text-muted mt-3 mb-0">El correo y el teléfono se muestran cuando apruebas la solicitud.</p>
            @endif
        </div>
    </div>

    <div class="col-lg-6">
        <div class="tarjeta-hu p-4 h-100">
            <h2 class="h5 fw-bold mb-3">Respuestas de la preadopción</h2>
            <dl class="row mb-0">
                <dt class="col-sm-5">Personas en el hogar</dt><dd class="col-sm-7">{{ $solicitud->numero_personas }}</dd>
                <dt class="col-sm-5">Tipo de vivienda</dt><dd class="col-sm-7">{{ ucfirst($solicitud->tipo_vivienda) }}</dd>
                <dt class="col-sm-5">Patio o jardín</dt><dd class="col-sm-7">{{ $solicitud->tiene_patio ? 'Sí' : 'No' }}</dd>
                <dt class="col-sm-5">Permiso del propietario</dt><dd class="col-sm-7">{{ $permiso }}</dd>
                <dt class="col-sm-5">Experiencia previa</dt><dd class="col-sm-7">{{ $solicitud->experiencia_previa ?: 'No indicó' }}</dd>
                <dt class="col-sm-5">Compromiso de tenencia</dt><dd class="col-sm-7">{{ $solicitud->acepta_compromiso ? 'Aceptado' : 'No aceptado' }}</dd>
            </dl>
        </div>
    </div>
</div>

<div class="tarjeta-hu p-4 mt-3">
    @if ($solicitud->estado === 'pendiente')
        <h2 class="h5 fw-bold mb-3">Responder solicitud</h2>
        <form method="POST" action="{{ route('albergue.solicitudes.responder', $solicitud) }}">
            @csrf
            @method('PATCH')
            <label for="respuesta" class="form-label">Mensaje para el adoptante</label>
            <textarea id="respuesta" name="respuesta" rows="3" maxlength="500"
                      class="form-control @error('respuesta') is-invalid @enderror">{{ old('respuesta') }}</textarea>
            <div class="form-text">Obligatorio al rechazar; opcional al aprobar.</div>
            @error('respuesta')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            @error('decision')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

            <div class="d-flex gap-2 mt-3">
                <button type="submit" name="decision" value="aprobada" class="btn btn-primary">Aprobar</button>
                <button type="submit" name="decision" value="rechazada" class="btn btn-outline-danger">Rechazar</button>
            </div>
        </form>
    @else
        <h2 class="h5 fw-bold mb-2">Respuesta enviada</h2>
        <p class="mb-0">{{ $solicitud->respuesta ?: 'No se escribió un mensaje.' }}</p>
    @endif
</div>
@endsection