<a href="{{ route('albergue.panel') }}"
   class="{{ request()->routeIs('albergue.panel', 'albergue.mascotas.*') ? 'active' : '' }}">Mis mascotas</a>
<a href="{{ route('albergue.solicitudes.index') }}"
   class="{{ request()->routeIs('albergue.solicitudes.*') ? 'active' : '' }}">Solicitudes recibidas</a>
<a href="{{ route('albergue.perfil.edit') }}"
   class="{{ request()->routeIs('albergue.perfil.*') ? 'active' : '' }}">Perfil del albergue</a>