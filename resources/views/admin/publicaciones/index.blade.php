@extends('layouts.panel')

@section('titulo', 'Publicaciones')

@section('contenido')
<h1 class="h3 fw-bold mb-1">Publicaciones</h1>
<p class="text-muted">Mascotas publicadas por los albergues. Elimina las que incumplan las normas de la plataforma.</p>

<div class="tarjeta-hu table-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Mascota</th>
                <th scope="col">Albergue</th>
                <th scope="col">Estado</th>
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
                <td>{{ $mascota->albergue->nombre ?? '—' }}</td>
                <td>{{ $mascota->estado_texto }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.publicaciones.destroy', $mascota) }}"
                          onsubmit="return confirm('¿Eliminar esta publicación? No se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">No hay publicaciones.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $mascotas->links('pagination::bootstrap-5') }}</div>
@endsection