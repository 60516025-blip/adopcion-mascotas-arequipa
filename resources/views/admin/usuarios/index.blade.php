@extends('layouts.panel')

@section('titulo', 'Usuarios')

@section('contenido')
@php
    $filtros = ['' => 'Todos', 'adoptante' => 'Adoptantes', 'albergue' => 'Albergues', 'admin' => 'Administradores'];
@endphp

<h1 class="h3 fw-bold mb-3">Usuarios</h1>

<ul class="nav nav-pills mb-3">
    @foreach ($filtros as $valor => $texto)
        <li class="nav-item">
            <a class="nav-link {{ (string) $rol === (string) $valor ? 'active' : '' }}"
               href="{{ route('admin.usuarios.index', $valor === '' ? [] : ['rol' => $valor]) }}">{{ $texto }}</a>
        </li>
    @endforeach
</ul>

<div class="tarjeta-hu table-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Correo</th>
                <th scope="col">Rol</th>
                <th scope="col">Distrito</th>
                <th scope="col">Registro</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($usuarios as $usuario)
            <tr>
                <td><strong>{{ $usuario->name }}</strong></td>
                <td>{{ $usuario->email }}</td>
                <td><span class="badge-hu badge-neutro">{{ ucfirst($usuario->rol) }}</span></td>
                <td>{{ $usuario->distrito ?: '—' }}</td>
                <td>{{ $usuario->created_at?->format('d/m/Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No hay usuarios en esta lista.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $usuarios->links('pagination::bootstrap-5') }}</div>
@endsection