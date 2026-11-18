@extends('layouts.panel')

@section('titulo', 'Perfil del albergue')

@section('contenido')
<h1 class="h3 fw-bold mb-1">Perfil del albergue</h1>
<p class="text-muted mb-3">
    Estado:
    <span class="badge-hu {{ $albergue->validado ? 'badge-ok' : 'badge-pend' }}">
        {{ $albergue->validado ? 'Validado' : 'Pendiente de validación' }}
    </span>
</p>

<form method="POST" action="{{ route('albergue.perfil.update') }}" novalidate>
    @csrf
    @method('PUT')

    <div class="tarjeta-hu p-4 mb-3">
        <div class="row g-3">
            <div class="col-md-8">
                <label for="nombre" class="form-label">Nombre del albergue</label>
                <input type="text" id="nombre" name="nombre" maxlength="120" required
                       value="{{ old('nombre', $albergue->nombre) }}"
                       class="form-control @error('nombre') is-invalid @enderror">
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="distrito" class="form-label">Distrito</label>
                <input type="text" id="distrito" name="distrito" maxlength="100" required
                       value="{{ old('distrito', $albergue->distrito) }}"
                       class="form-control @error('distrito') is-invalid @enderror">
                @error('distrito')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4" maxlength="1000"
                          class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $albergue->descripcion) }}</textarea>
                <div class="form-text">Cuenta quiénes son y cómo trabajan. Se mostrará a los adoptantes.</div>
                @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Guardar cambios</button>
</form>
@endsection