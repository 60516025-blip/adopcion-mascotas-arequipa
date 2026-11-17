<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Panel') · {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/huellitas.css') }}" rel="stylesheet">
</head>
<body>
<header class="navbar navbar-hu px-3 justify-content-between">
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
    <div class="d-flex align-items-center gap-2">
        <span class="small">{{ auth()->user()->albergue->nombre ?? auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">Cerrar sesión</button>
        </form>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 p-3 sidebar-hu" aria-label="Menú del panel">
            @includeIf(auth()->user()->esAdmin() ? 'partials.sidebar-admin' : 'partials.sidebar-albergue')
        </nav>
        <main class="col-md-9 col-lg-10 p-4">
            @if (session('exito'))
                <div class="alert alert-success" role="alert">{{ session('exito') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif

            @yield('contenido')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>