<a href="{{ route('admin.dashboard') }}"
   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Métricas de impacto</a>
<a href="{{ route('admin.albergues.index') }}"
   class="{{ request()->routeIs('admin.albergues.*') ? 'active' : '' }}">Validar albergues</a>
<a href="{{ route('admin.usuarios.index') }}"
   class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">Usuarios</a>
<a href="{{ route('admin.publicaciones.index') }}"
   class="{{ request()->routeIs('admin.publicaciones.*') ? 'active' : '' }}">Publicaciones</a>