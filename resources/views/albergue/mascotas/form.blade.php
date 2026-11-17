@extends('layouts.panel')

@section('titulo', $mascota->exists ? 'Editar mascota' : 'Agregar mascota')

@section('contenido')
@php
    $editando = $mascota->exists;
    $ficha = $mascota->fichaMedica;
    $conError = session()->hasOldInput();
    // Casillas: si el formulario volvió con errores se respeta lo marcado
    $marcado = fn (string $campo) => $conError ? (bool) old($campo) : (bool) ($ficha?->{$campo} ?? false);
    $valor = fn (string $campo, $defecto = null) => old($campo, $defecto);
@endphp

<h1 class="h3 fw-bold mb-3">{{ $editando ? 'Editar mascota' : 'Agregar mascota' }}</h1>

<form method="POST" enctype="multipart/form-data" novalidate
      action="{{ $editando ? route('albergue.mascotas.update', $mascota) : route('albergue.mascotas.store') }}">
    @csrf
    @if ($editando) @method('PUT') @endif

    <div class="tarjeta-hu p-4 mb-3">
        <h2 class="h5 fw-bold mb-3">Datos de la mascota</h2>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" maxlength="100" required
                       value="{{ $valor('nombre', $mascota->nombre) }}"
                       class="form-control @error('nombre') is-invalid @enderror">
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="especie" class="form-label">Especie</label>
                <select id="especie" name="especie" required class="form-select @error('especie') is-invalid @enderror">
                    <option value="">Seleccionar</option>
                    <option value="perro" @selected($valor('especie', $mascota->especie) === 'perro')>Perro</option>
                    <option value="gato" @selected($valor('especie', $mascota->especie) === 'gato')>Gato</option>
                </select>
                @error('especie')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="genero" class="form-label">Sexo</label>
                <select id="genero" name="genero" required class="form-select @error('genero') is-invalid @enderror">
                    <option value="">Seleccionar</option>
                    <option value="macho" @selected($valor('genero', $mascota->genero) === 'macho')>Macho</option>
                    <option value="hembra" @selected($valor('genero', $mascota->genero) === 'hembra')>Hembra</option>
                </select>
                @error('genero')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label for="raza" class="form-label">Raza (opcional)</label>
                <input type="text" id="raza" name="raza" maxlength="100"
                       value="{{ $valor('raza', $mascota->raza) }}"
                       class="form-control @error('raza') is-invalid @enderror">
                @error('raza')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="edad_meses" class="form-label">Edad en meses</label>
                <input type="number" id="edad_meses" name="edad_meses" min="0" max="300" required
                       value="{{ $valor('edad_meses', $mascota->edad_meses) }}"
                       class="form-control @error('edad_meses') is-invalid @enderror">
                <div class="form-text">Ejemplo: 24 equivale a 2 años.</div>
                @error('edad_meses')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="tamano" class="form-label">Tamaño</label>
                <select id="tamano" name="tamano" required class="form-select @error('tamano') is-invalid @enderror">
                    <option value="">Seleccionar</option>
                    <option value="pequeno" @selected($valor('tamano', $mascota->tamano) === 'pequeno')>Pequeño</option>
                    <option value="mediano" @selected($valor('tamano', $mascota->tamano) === 'mediano')>Mediano</option>
                    <option value="grande" @selected($valor('tamano', $mascota->tamano) === 'grande')>Grande</option>
                </select>
                @error('tamano')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3" maxlength="1000"
                          class="form-control @error('descripcion') is-invalid @enderror">{{ $valor('descripcion', $mascota->descripcion) }}</textarea>
                @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="foto" class="form-label">Foto</label>
                @if ($editando && $mascota->foto_url)
                    <div class="mb-2">
                        <img src="{{ asset($mascota->foto_url) }}" alt="Foto actual de {{ $mascota->nombre }}"
                             class="rounded" style="height:90px">
                    </div>
                @endif
                <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp"
                       class="form-control @error('foto') is-invalid @enderror">
                <div class="form-text">JPG, PNG o WEBP, máximo 2 MB. Si subes otra, reemplaza la actual.</div>
                @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="tarjeta-hu p-4 mb-3">
        <h2 class="h5 fw-bold mb-3">Ficha sanitaria</h2>
        <div class="row g-2 mb-3">
            @foreach ([
                'vacuna_antirrabica' => 'Vacuna antirrábica',
                'vacuna_quintuple' => 'Vacuna quíntuple',
                'desparasitado' => 'Desparasitado',
                'esterilizado' => 'Esterilizado',
            ] as $campo => $etiqueta)
                <div class="col-sm-6 col-lg-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="{{ $campo }}" id="{{ $campo }}" value="1"
                               @checked($marcado($campo))>
                        <label class="form-check-label" for="{{ $campo }}">{{ $etiqueta }}</label>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <label for="fecha_ultima_revision" class="form-label">Última revisión (opcional)</label>
                <input type="date" id="fecha_ultima_revision" name="fecha_ultima_revision"
                       value="{{ $valor('fecha_ultima_revision', $ficha?->fecha_ultima_revision?->format('Y-m-d')) }}"
                       class="form-control @error('fecha_ultima_revision') is-invalid @enderror">
                @error('fecha_ultima_revision')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
                <label for="observaciones" class="form-label">Observaciones (opcional)</label>
                <textarea id="observaciones" name="observaciones" rows="2" maxlength="500"
                          class="form-control @error('observaciones') is-invalid @enderror">{{ $valor('observaciones', $ficha?->observaciones) }}</textarea>
                @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">{{ $editando ? 'Guardar cambios' : 'Publicar mascota' }}</button>
        <a href="{{ route('albergue.panel') }}" class="btn btn-outline-primary">Cancelar</a>
    </div>
</form>
@endsection