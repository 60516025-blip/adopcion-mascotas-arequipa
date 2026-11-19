@extends('layouts.app')

@section('titulo', 'Albergues')

@section('contenido')
<div class="container py-4">
    <h1 class="fw-bold">Albergues y rescatistas</h1>
    <p class="text-muted">Organizaciones validadas que publican mascotas en adopción en Arequipa.</p>

    <form method="GET" action="{{ route('albergues.index') }}" class="row g-2 align-items-end mb-3">
        <div class="col-sm-6 col-md-4">
            <label for="distrito" class="form-label">Distrito</label>
            <select id="distrito" name="distrito" class="form-select" onchange="this.form.submit()">
                <option value="">Todos los distritos</option>
                @foreach ($distritos as $d)
                    <option value="{{ $d }}" @selected($distrito === $d)>{{ $d }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <noscript><button type="submit" class="btn btn-primary">Filtrar</button></noscript>
            @if ($distrito)
                <a href="{{ route('albergues.index') }}" class="btn btn-outline-primary">Limpiar</a>
            @endif
        </div>
    </form>

    @if ($cercanos)
        <p class="small text-muted">Mostramos primero los albergues de tu distrito ({{ $miDistrito }}).</p>
    @endif

    @if ($albergues->isEmpty())
        <div class="tarjeta-hu p-4 text-center text-muted">No hay albergues validados en esta zona todavía.</div>
    @else
        <div class="row g-3">
            @foreach ($albergues as $albergue)
                <div class="col-md-6 col-lg-4">
                    <div class="tarjeta-hu p-4 h-100 d-flex flex-column">
                        <h2 class="h5 fw-bold mb-1">{{ $albergue->nombre }}</h2>
                        <p class="small text-muted mb-2">{{ $albergue->distrito }} · Arequipa</p>
                        <p class="mb-3">{{ \Illuminate\Support\Str::limit($albergue->descripcion ?: 'Este albergue aún no agregó una descripción.', 120) }}</p>
                        <div class="mb-3">
                            <span class="badge-hu {{ $albergue->disponibles_count > 0 ? 'badge-ok' : 'badge-neutro' }}">
                                {{ $albergue->disponibles_count }} {{ $albergue->disponibles_count === 1 ? 'mascota disponible' : 'mascotas disponibles' }}
                            </span>
                        </div>
                        <a href="{{ route('albergues.show', $albergue->id) }}" class="btn btn-outline-primary btn-sm w-100 mt-auto">Ver albergue</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $albergues->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection