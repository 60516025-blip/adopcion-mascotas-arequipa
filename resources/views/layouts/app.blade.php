<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Inicio') · {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/huellitas.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-hu">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('inicio') }}" style="color:#BF4513">
            <svg class="marca-pata" viewBox="0 0 64 64" aria-hidden="true" focusable="false">
                <ellipse cx="13" cy="28" rx="6" ry="8" transform="rotate(-20 13 28)"/>
                <ellipse cx="25" cy="15" rx="6" ry="8" transform="rotate(-8 25 15)"/>
                <ellipse cx="39" cy="15" rx="6" ry="8" transform="rotate(8 39 15)"/>
                <ellipse cx="51" cy="28" rx="6" ry="8" transform="rotate(20 51 28)"/>
                <path d="M32 33c-9 0-17 8-17 15 0 5 4 7 9 7 3 0 5-1 8-1s5 1 8 1c5 0 9-2 9-7 0-7-8-15-17-15z"/>
            </svg>
            {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}" href="{{ route('inicio') }}">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Catálogo</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Albergues</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Cómo adoptar</a></li>
            </ul>
            <div class="d-flex gap-2 align-items-center">
                @auth
                    <span class="small">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm">Cerrar sesión</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Iniciar sesión</a>
                    <a href="{{ route('registro') }}" class="btn btn-primary btn-sm">Registrarse</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

@if (session('exito'))
    <div class="container mt-3">
        <div class="alert alert-success mb-0" role="alert">{{ session('exito') }}</div>
    </div>
@endif

<main>
    @yield('contenido')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>