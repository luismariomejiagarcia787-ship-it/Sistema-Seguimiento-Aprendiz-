@php $isEdit = isset($instructor); @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nombre completo *</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               name="name" value="{{ old('name', $instructor->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Email *</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
               name="email" value="{{ old('email', $instructor->email ?? '') }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">
            Contraseña {{ $isEdit ? '<span class="text-muted fw-normal">(dejar vacío para no cambiar)</span>' : '*' }}
        </label>
        <input type="password" class="form-control @error('password') is-invalid @enderror"
               name="password" {{ $isEdit ? '' : 'required' }}>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Confirmar Contraseña</label>
        <input type="password" class="form-control" name="password_confirmation">
    </div>
    <div class="col-md-6">
        <label class="form-label">Teléfono</label>
        <input type="text" class="form-control @error('telefono') is-invalid @enderror"
               name="telefono" value="{{ old('telefono', $instructor->telefono ?? '') }}"
               placeholder="300 000 0000">
        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Foto de Perfil <small class="text-muted">(opcional)</small></label>
        @if($isEdit && isset($instructor) && $instructor->foto)
        <div class="mb-2 d-flex align-items-center gap-2">
            <img src="{{ $instructor->foto_url }}" class="rounded"
                 style="height:44px;width:44px;object-fit:cover;">
            <small class="text-muted">Foto actual</small>
        </div>
        @endif
        <input type="file" class="form-control @error('foto') is-invalid @enderror"
               name="foto" accept="image/*">
        @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Asignación de Fichas --}}
<div class="mt-4 p-3 rounded-3" style="background:#f0f4ff;border:1px solid #c7d2fe;">
    <h6 class="fw-bold mb-1"><i class="bi bi-collection me-2" style="color:#4338ca;"></i>Fichas Asignadas</h6>
    <p class="text-muted mb-3" style="font-size:12px;">
        Selecciona las fichas que este instructor puede gestionar o escribe el número directamente.
    </p>

    <div id="fichasContainer">
        {{-- Renderizar fichas ya seleccionadas (edición) --}}
        @foreach(old('fichas', $fichasSeleccionadas ?? []) as $fc)
        @if(!empty(trim($fc)))
        <div class="d-flex gap-2 mb-2 ficha-row">
            <input type="text" class="form-control" name="fichas[]"
                   value="{{ $fc }}" placeholder="Número de ficha (ej: 2345678)">
            <button type="button" class="btn btn-outline-danger btn-sm"
                    onclick="this.closest('.ficha-row').remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        @endif
        @endforeach
    </div>

    <div class="d-flex gap-2 mt-3 align-items-center">
        {{-- Selector rápido de fichas existentes --}}
        @if(!empty($fichasExistentes))
        <select class="form-select" id="fichaSelectRapido" style="max-width:220px;">
            <option value="">Ficha existente...</option>
            @foreach($fichasExistentes as $fe)
            <option value="{{ $fe }}">Ficha {{ $fe }}</option>
            @endforeach
        </select>
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarDesdeSelect()">
            <i class="bi bi-plus-lg me-1"></i>Agregar
        </button>
        <span class="text-muted mx-1" style="font-size:12px;">ó</span>
        @endif
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="agregarFichaNueva()">
            <i class="bi bi-keyboard me-1"></i>Ingresar manualmente
        </button>
    </div>
</div>

@push('scripts')
<script>
function agregarDesdeSelect() {
    const sel = document.getElementById('fichaSelectRapido');
    if (!sel || !sel.value) return;
    _addFichaRow(sel.value);
    sel.value = '';
}

function agregarFichaNueva() {
    _addFichaRow('');
}

function _addFichaRow(value) {
    const container = document.getElementById('fichasContainer');
    const row = document.createElement('div');
    row.className = 'd-flex gap-2 mb-2 ficha-row';
    row.innerHTML = `
        <input type="text" class="form-control" name="fichas[]"
               value="${value}" placeholder="Número de ficha (ej: 2345678)">
        <button type="button" class="btn btn-outline-danger btn-sm"
                onclick="this.closest('.ficha-row').remove()">
            <i class="bi bi-x-lg"></i>
        </button>`;
    container.appendChild(row);
    row.querySelector('input').focus();
}
</script>
@endpush
