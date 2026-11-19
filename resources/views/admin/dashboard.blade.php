@extends('layouts.panel')

@section('titulo', 'Métricas de impacto')

@section('contenido')
<h1 class="h3 fw-bold mb-3">Métricas de impacto</h1>

<div class="row g-3 mb-4">
    @foreach ([
        ['Adopciones concretadas', $metricas['adopciones']],
        ['Mascotas disponibles', $metricas['disponibles']],
        ['Solicitudes pendientes', $metricas['pendientes']],
        ['Tiempo promedio de adopción', is_null($metricas['dias']) ? 'Sin datos' : $metricas['dias'] . ' días'],
    ] as [$etiqueta, $valor])
        <div class="col-sm-6 col-xl-3">
            <div class="tarjeta-hu p-3 h-100">
                <div class="small text-muted">{{ $etiqueta }}</div>
                <div class="fs-2 fw-bold" style="color:#BF4513">{{ $valor }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="tarjeta-hu p-4 mb-4">
    <h2 class="h5 fw-bold mb-3">Adopciones por mes (últimos 6 meses)</h2>
    <div class="d-flex gap-3">
        @foreach ($meses as $mes)
            <div class="flex-fill text-center">
                <div class="small fw-semibold">{{ $mes['total'] }}</div>
                <div class="d-flex align-items-end" style="height:140px">
                    <div class="w-100" title="{{ $mes['total'] }} adopciones"
                        @style(['height: ' . round($mes['total'] / $maximo * 100) . '%', 'min-height: 3px', 'background: #BF4513', 'border-radius: 6px 6px 0 0'])></div>
                </div>
                <small class="text-muted">{{ $mes['etiqueta'] }}</small>
            </div>
        @endforeach
    </div>
    <p class="small text-muted mt-3 mb-0">Se cuenta desde que el albergue marca la mascota como "Adoptada".</p>
</div>

<h2 class="h5 fw-bold mb-3">Albergues pendientes de validación</h2>
<div class="tarjeta-hu table-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr><th scope="col">Albergue</th><th scope="col">Distrito</th><th scope="col">Acciones</th></tr>
        </thead>
        <tbody>
        @forelse ($pendientes as $albergue)
            <tr>
                <td><strong>{{ $albergue->nombre }}</strong></td>
                <td>{{ $albergue->distrito }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.albergues.actualizar', $albergue) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="validado" value="1">
                        <button type="submit" class="btn btn-primary btn-sm">Validar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="text-center text-muted py-4">No hay albergues pendientes.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection