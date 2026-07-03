@extends('layouts.app')
@section('title', 'Subir Evidencia')
@section('breadcrumb', 'Evidencias > Subir')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-upload me-2" style="color:#39A900;"></i>Subir Evidencia</h4>
    <p>Selecciona la actividad y adjunta tu archivo de evidencia</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="table-card p-4">
            @if($actividades->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-journal-x fs-1 text-muted opacity-50 d-block mb-3"></i>
                <h6 class="text-muted">No tienes actividades pendientes</h6>
                <p class="text-muted" style="font-size:13px;">
                    Cuando un instructor te asigne actividades aparecerán aquí.
                </p>
                <a href="{{ route('evidencias.index') }}" class="btn btn-outline-secondary mt-2">
                    <i class="bi bi-arrow-left me-1"></i>Volver a Evidencias
                </a>
            </div>
            @else
            <form method="POST" action="{{ route('evidencias.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Selección de actividad -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Actividad *</label>
                    <select class="form-select form-select-lg @error('actividad_id') is-invalid @enderror"
                            name="actividad_id" required>
                        <option value="">— Selecciona la actividad —</option>
                        @foreach($actividades as $act)
                        <option value="{{ $act->id }}"
                            {{ (old('actividad_id', request('actividad_id')) == $act->id) ? 'selected' : '' }}>
                            {{ $act->titulo }}
                            (Límite: {{ $act->fecha_limite->format('d/m/Y') }})
                            {{ $act->estaVencida() ? ' ⚠️ Vencida' : '' }}
                        </option>
                        @endforeach
                    </select>
                    @error('actividad_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Zona de carga -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Archivo de Evidencia *</label>
                    <div class="upload-area text-center p-5 rounded-3"
                         id="uploadArea"
                         style="border:2px dashed #d1d5db;cursor:pointer;transition:all 0.2s;"
                         onclick="document.getElementById('archivo').click()">
                        <i class="bi bi-cloud-upload-fill fs-1" style="color:#9ca3af;"></i>
                        <p class="mt-2 mb-1 fw-semibold" style="color:#374151;">
                            Arrastra tu archivo aquí o haz clic
                        </p>
                        <p class="text-muted mb-0" style="font-size:12px;">
                            PDF, Word (.doc, .docx), Imágenes (JPG, PNG), ZIP — Máximo 10 MB
                        </p>
                        <input type="file" id="archivo" name="archivo" class="d-none"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip" required>
                    </div>
                    @error('archivo')
                    <div class="text-danger mt-1" style="font-size:13px;">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror

                    <!-- Preview del archivo -->
                    <div id="filePreview" class="mt-3 d-none">
                        <div class="p-3 rounded-3 d-flex align-items-center gap-3"
                             style="background:#f0fdf4;border:1px solid #bbf7d0;">
                            <i class="bi bi-file-earmark-check text-success fs-3"></i>
                            <div>
                                <div id="fileName" class="fw-semibold" style="font-size:14px;"></div>
                                <div id="fileSize" class="text-muted" style="font-size:12px;"></div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-auto"
                                    onclick="clearFile()">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sena text-white px-5">
                        <i class="bi bi-send me-2"></i>Enviar Evidencia
                    </button>
                    <a href="{{ route('evidencias.index') }}" class="btn btn-outline-secondary px-4">
                        Cancelar
                    </a>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
const fileInput = document.getElementById('archivo');
const uploadArea = document.getElementById('uploadArea');
const filePreview = document.getElementById('filePreview');

if (fileInput) {
    fileInput.addEventListener('change', function () {
        if (this.files[0]) showFile(this.files[0]);
    });

    uploadArea.addEventListener('dragover', e => {
        e.preventDefault();
        uploadArea.style.borderColor = '#39A900';
        uploadArea.style.background = '#f0fdf4';
    });
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.style.borderColor = '#d1d5db';
        uploadArea.style.background = '';
    });
    uploadArea.addEventListener('drop', e => {
        e.preventDefault();
        uploadArea.style.borderColor = '#d1d5db';
        uploadArea.style.background = '';
        if (e.dataTransfer.files[0]) {
            fileInput.files = e.dataTransfer.files;
            showFile(e.dataTransfer.files[0]);
        }
    });
}

function showFile(file) {
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    filePreview.classList.remove('d-none');
    uploadArea.style.borderColor = '#39A900';
}

function clearFile() {
    fileInput.value = '';
    filePreview.classList.add('d-none');
    uploadArea.style.borderColor = '#d1d5db';
}
</script>
@endpush
@endsection
