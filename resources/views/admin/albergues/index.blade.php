@extends('layouts.panel')

@section('titulo', 'Validar albergues')

@section('contenido')
@php
    $filtros = ['' => 'Todos', 'pendiente' => 'Pendientes', 'validado' => 'Validados'];
@endphp

<h1 class="h3 fw-bold mb-1">Validar albergues</h1>
<p class="text-muted">Solo los albergues validados pueden publicar mascotas y aparecer en el catálogo.</p>

<ul class="nav nav-pills mb-3">
    @foreach ($filtros as $valor => $texto)
        <li class="nav-item">
            <a class="nav-link {{ (string) $estado === (string) $valor ? 'active' : '' }}"
               href="{{ route('admin.albergues.index', $valor === '' ? [] : ['estado' => $valor]) }}">{{ $texto }}</a>
        </li>
    @endforeach
</ul>

<div class="tarjeta-hu table-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Albergue</th>
                <th scope="col">Distrito</th>
                <th scope="col">Responsable</th>
                <th scope="col">Mascotas</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($albergues as $albergue)
            @php $responsable = $responsables[$albergue->user_id] ?? null; @endphp
            <tr>
                <td>
                    <strong>{{ $albergue->nombre }}</strong>
                    @if ($albergue->descripcion)
                        <div class="small text-muted">{{ \Illuminate\Support\Str::limit($albergue->descripcion, 70) }}</div>
                    @endif
                </td>
                <td>{{ $albergue->distrito }}</td>
                <td>
                    {{ $responsable?->name ?? '—' }}
                    <div class="small text-muted">{{ $responsable?->email }}</div>
                </td>
                <td>{{ $albergue->mascotas_count }}</td>
                <td>
                    <span class="badge-hu {{ $albergue->validado ? 'badge-ok' : 'badge-pend' }}">
                        {{ $albergue->validado ? 'Validado' : 'Pendiente' }}
                    </span>
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.albergues.actualizar', $albergue) }}">
                        @csrf
                        @method('PATCH')
                        @if ($albergue->validado)
                            <input type="hidden" name="validado" value="0">
                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('¿Quitar la validación? Sus mascotas dejarán de verse en el catálogo.')">Quitar validación</button>
                        @else
                            <input type="hidden" name="validado" value="1">
                            <button type="submit" class="btn btn-primary btn-sm">Validar</button>
                        @endif
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No hay albergues en esta lista.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $albergues->links('pagination::bootstrap-5') }}</div>
@endsection