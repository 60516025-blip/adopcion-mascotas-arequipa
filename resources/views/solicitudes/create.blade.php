@extends('layouts.app')

@section('titulo', 'Solicitud de adopción')

@section('contenido')
@php $usuario = auth()->user(); @endphp

<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="tarjeta-hu p-4">
                <h1 class="h3 fw-bold mb-1">Solicitud de adopción</h1>
                <p class="text-muted">Responde con sinceridad: esta información ayuda al albergue a encontrar el mejor hogar para {{ $mascota->nombre }}.</p>

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        Revisa los datos marcados en rojo:
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="pasos-hu" aria-label="Pasos de la solicitud">
                    <div class="paso activo"><span>1</span> Datos</div>
                    <div class="paso"><span>2</span> Vivienda</div>
                    <div class="paso"><span>3</span> Experiencia</div>
                    <div class="paso"><span>4</span> Compromiso</div>
                </div>

                <form id="form-preadopcion" method="POST" action="{{ route('solicitudes.store', $mascota->id) }}">
                    @csrf

                    {{-- Paso 1: Datos --}}
                    <div class="paso-form">
                        <h2 class="h5 fw-bold mb-3">Tus datos</h2>
                        <dl class="row">
                            <dt class="col-sm-4">Nombre</dt><dd class="col-sm-8">{{ $usuario->name }}</dd>
                            <dt class="col-sm-4">Correo</dt><dd class="col-sm-8">{{ $usuario->email }}</dd>
                            <dt class="col-sm-4">Distrito</dt><dd class="col-sm-8">{{ $usuario->distrito ?: 'No registrado' }}</dd>
                        </dl>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="telefono" class="form-label">Teléfono de contacto</label>
                                <input type="tel" id="telefono" name="telefono" required maxlength="20"
                                       value="{{ old('telefono', $usuario->telefono) }}" placeholder="Ejemplo: 987654321"
                                       class="form-control @error('telefono') is-invalid @enderror">
                                <div class="form-text">Solo se mostrará al albergue si aprueba tu solicitud.</div>
                                @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="numero_personas" class="form-label">Personas en el hogar</label>
                                <input type="number" id="numero_personas" name="numero_personas" min="1" max="30" required
                                       value="{{ old('numero_personas') }}" placeholder="Ejemplo: 4"
                                       class="form-control @error('numero_personas') is-invalid @enderror">
                                @error('numero_personas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Paso 2: Vivienda --}}
                    <div class="paso-form">
                        <h2 class="h5 fw-bold mb-3">Vivienda</h2>

                        <p class="mb-1 fw-semibold">La vivienda es…</p>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo_vivienda" id="vivienda_propia" value="propia" required @checked(old('tipo_vivienda') === 'propia')>
                                <label class="form-check-label" for="vivienda_propia">Propia</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo_vivienda" id="vivienda_alquilada" value="alquilada" @checked(old('tipo_vivienda') === 'alquilada')>
                                <label class="form-check-label" for="vivienda_alquilada">Alquilada</label>
                            </div>
                            @error('tipo_vivienda')<div class="text-danger small campo-error">{{ $message }}</div>@enderror
                        </div>

                        <p class="mb-1 fw-semibold">¿Cuenta con patio, jardín o terraza?</p>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tiene_patio" id="patio_si" value="1" required @checked(old('tiene_patio') === '1')>
                                <label class="form-check-label" for="patio_si">Sí</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tiene_patio" id="patio_no" value="0" @checked(old('tiene_patio') === '0')>
                                <label class="form-check-label" for="patio_no">No</label>
                            </div>
                            @error('tiene_patio')<div class="text-danger small campo-error">{{ $message }}</div>@enderror
                        </div>

                        <div id="bloque-permiso" class="d-none">
                            <p class="mb-1 fw-semibold">Si alquilas, ¿el propietario permite mascotas?</p>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="permiso_mascotas" id="permiso_si" value="1" @checked(old('permiso_mascotas') === '1')>
                                <label class="form-check-label" for="permiso_si">Sí</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="permiso_mascotas" id="permiso_no" value="0" @checked(old('permiso_mascotas') === '0')>
                                <label class="form-check-label" for="permiso_no">No</label>
                            </div>
                            @error('permiso_mascotas')<div class="text-danger small campo-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Paso 3: Experiencia --}}
                    <div class="paso-form">
                        <h2 class="h5 fw-bold mb-3">Experiencia con mascotas</h2>
                        <label for="experiencia_previa" class="form-label">Cuéntanos si has tenido mascotas antes (opcional)</label>
                        <textarea id="experiencia_previa" name="experiencia_previa" rows="4" maxlength="1000"
                                  class="form-control @error('experiencia_previa') is-invalid @enderror">{{ old('experiencia_previa') }}</textarea>
                        @error('experiencia_previa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Paso 4: Compromiso --}}
                    <div class="paso-form">
                        <h2 class="h5 fw-bold mb-3">Compromiso de tenencia responsable</h2>
                        <ul>
                            <li>Brindaré alimentación, agua, abrigo y atención veterinaria a la mascota.</li>
                            <li>Mantendré sus vacunas al día y consideraré su esterilización.</li>
                            <li>No la abandonaré; si ya no puedo cuidarla, avisaré al albergue.</li>
                            <li>Entiendo que la decisión final la toma el albergue.</li>
                        </ul>
                        <div class="form-check">
                            <input class="form-check-input @error('acepta_compromiso') is-invalid @enderror" type="checkbox"
                                   name="acepta_compromiso" id="acepta_compromiso" value="1" required @checked(old('acepta_compromiso'))>
                            <label class="form-check-label" for="acepta_compromiso">Acepto el compromiso de tenencia responsable.</label>
                            @error('acepta_compromiso')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" id="btn-anterior" class="btn btn-outline-primary d-none">&lsaquo; Anterior</button>
                        <span></span>
                        <div>
                            <button type="button" id="btn-siguiente" class="btn btn-primary">Siguiente &rsaquo;</button>
                            <button type="submit" id="btn-enviar" class="btn btn-primary d-none">Enviar solicitud</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="tarjeta-hu p-3">
                @if ($mascota->foto_url)
                    <img src="{{ asset($mascota->foto_url) }}" alt="Foto de {{ $mascota->nombre }}" class="img-fluid rounded mb-3">
                @endif
                <h2 class="h5 fw-bold mb-1">{{ $mascota->nombre }}</h2>
                <p class="text-muted mb-1">{{ ucfirst($mascota->especie) }}</p>
                <p class="small mb-0">Albergue: {{ $mascota->albergue->nombre }}</p>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const form = document.getElementById('form-preadopcion');
    const pasos = Array.from(form.querySelectorAll('.paso-form'));
    const indicadores = Array.from(document.querySelectorAll('.pasos-hu .paso'));
    const btnAnt = document.getElementById('btn-anterior');
    const btnSig = document.getElementById('btn-siguiente');
    const btnEnv = document.getElementById('btn-enviar');
    let actual = 0;

    function mostrar(n) {
        actual = n;
        pasos.forEach((p, i) => p.classList.toggle('d-none', i !== n));
        indicadores.forEach((el, i) => {
            el.classList.toggle('activo', i === n);
            el.classList.toggle('hecho', i < n);
        });
        btnAnt.classList.toggle('d-none', n === 0);
        btnSig.classList.toggle('d-none', n === pasos.length - 1);
        btnEnv.classList.toggle('d-none', n !== pasos.length - 1);
    }

    // Valida solo los campos del paso visible antes de avanzar
    function pasoValido() {
        for (const campo of pasos[actual].querySelectorAll('input, select, textarea')) {
            if (!campo.checkValidity()) { campo.reportValidity(); return false; }
        }
        return true;
    }

    btnSig.addEventListener('click', () => { if (pasoValido()) mostrar(actual + 1); });
    btnAnt.addEventListener('click', () => mostrar(actual - 1));

    // Con Enter se avanza de paso en lugar de enviar el formulario
    form.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            if (actual < pasos.length - 1) btnSig.click();
        }
    });

    // Vivienda alquilada: se muestra y se exige el permiso del propietario
    const bloquePermiso = document.getElementById('bloque-permiso');
    const permisos = form.querySelectorAll('input[name="permiso_mascotas"]');
    function actualizarPermiso() {
        const marcado = form.querySelector('input[name="tipo_vivienda"]:checked');
        const alquilada = marcado && marcado.value === 'alquilada';
        bloquePermiso.classList.toggle('d-none', !alquilada);
        permisos.forEach((r) => { r.required = alquilada; });
    }
    form.querySelectorAll('input[name="tipo_vivienda"]').forEach((r) => r.addEventListener('change', actualizarPermiso));
    actualizarPermiso();

    form.addEventListener('submit', () => { btnEnv.disabled = true; });

    // Si el servidor devolvió errores, abrir el paso donde está el primero
    const conError = pasos.findIndex((p) => p.querySelector('.is-invalid, .campo-error'));
    mostrar(conError === -1 ? 0 : conError);
})();
</script>
@endsection