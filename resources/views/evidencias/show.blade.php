@extends('layouts.app')
@section('title', 'Detalle Evidencia')
@section('breadcrumb', 'Evidencias > Detalle')

@section('content')
<div class="page-header d-flex justify-content-between">
    <div>
        <h4><i class="bi bi-file-earmark-check me-2" style="color:#dc2626;"></i>Detalle de Evidencia</h4>
    </div>
    <div class="d-flex gap-2">
        @if(auth()->user()->esAdministrador() || auth()->user()->esInstructor())
        <form method="POST" action="{{ route('evidencias.destroy', $entrega) }}"
              onsubmit="return confirm('¿Eliminar esta evidencia?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash me-1"></i>Eliminar
            </button>
        </form>
        @endif
        <a href="{{ route('evidencias.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="table-card p-4">
            <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3" style="background:#f8f9fa;">
                <i class="bi {{ $entrega->icono_archivo }}" style="font-size:48px;"></i>
                <div>
                    <div style="font-size:16px;font-weight:700;">{{ $entrega->nombre_archivo }}</div>
                    <div style="font-size:12px;color:#6c757d;">{{ $entrega->tipo_archivo }}</div>
                    <div class="mt-1">{!! $entrega->estado_badge !!}</div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label text-muted" style="font-size:11px;text-transform:uppercase;">Aprendiz</label>
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ $entrega->aprendiz->user->foto_url }}" class="avatar-sm" alt="">
                        <div>
                            <div style="font-size:14px;font-weight:600;">{{ $entrega->aprendiz->user->name }}</div>
                            <div style="font-size:12px;color:#6c757d;">Ficha {{ $entrega->aprendiz->ficha }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted" style="font-size:11px;text-transform:uppercase;">Actividad</label>
                    <div style="font-size:14px;font-weight:600;">{{ $entrega->actividad->titulo }}</div>
                    <div style="font-size:12px;color:#6c757d;">Instructor: {{ $entrega->actividad->instructor->name }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted" style="font-size:11px;text-transform:uppercase;">Fecha de Envío</label>
                    <div style="font-size:14px;">{{ $entrega->created_at->format('d/m/Y H:i') }}</div>
                </div>
                @if($entrega->revisado_por)
                <div class="col-md-6">
                    <label class="form-label text-muted" style="font-size:11px;text-transform:uppercase;">Revisado por</label>
                    <div style="font-size:14px;">{{ $entrega->revisor->name }}</div>
                    <div style="font-size:12px;color:#6c757d;">{{ $entrega->fecha_revision->format('d/m/Y H:i') }}</div>
                </div>
                @endif
            </div>

            @if($entrega->observacion)
            <div class="p-3 rounded-3 mb-4" style="background:#fef9c3;border-left:4px solid #f59e0b;">
                <label class="form-label text-muted mb-1" style="font-size:11px;text-transform:uppercase;">
                    Observación del Instructor
                </label>
                <p style="font-size:14px;margin:0;">{{ $entrega->observacion }}</p>
            </div>
            @endif

            <a href="{{ route('evidencias.download', $entrega) }}" class="btn btn-primary">
                <i class="bi bi-download me-2"></i>Descargar Archivo
            </a>
        </div>
    </div>

    {{-- Panel de revisión: solo instructor/admin y cuando está en revisión --}}
    @if((auth()->user()->esAdministrador() || auth()->user()->esInstructor()) && $entrega->estado === 'en_revision')
    <div class="col-lg-5">
        <div class="table-card p-4">
            <h6 class="fw-semibold mb-4"><i class="bi bi-clipboard-check me-2"></i>Revisar Evidencia</h6>
            <form method="POST" action="{{ route('evidencias.revisar', $entrega) }}">
                @csrf @method('PATCH')
                <div class="mb-3">
                    <label class="form-label">Decisión *</label>
                    <div class="d-flex gap-3">
                        <div class="form-check flex-fill text-center p-3 rounded-3"
                             style="border:2px solid #e5e7eb;cursor:pointer;" id="optAprobada">
                            <input class="form-check-input" type="radio" name="estado"
                                   value="aprobada" id="aprobada" required>
                            <label class="form-check-label d-block mt-1" for="aprobada" style="cursor:pointer;">
                                <i class="bi bi-check-circle-fill text-success fs-4 d-block mb-1"></i>
                                <span style="font-size:13px;font-weight:600;">Aprobar</span>
                            </label>
                        </div>
                        <div class="form-check flex-fill text-center p-3 rounded-3"
                             style="border:2px solid #e5e7eb;cursor:pointer;" id="optRechazada">
                            <input class="form-check-input" type="radio" name="estado"
                                   value="rechazada" id="rechazada">
                            <label class="form-check-label d-block mt-1" for="rechazada" style="cursor:pointer;">
                                <i class="bi bi-x-circle-fill text-danger fs-4 d-block mb-1"></i>
                                <span style="font-size:13px;font-weight:600;">Rechazar</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Observación <span class="text-muted">(opcional)</span></label>
                    <textarea class="form-control" name="observacion" rows="4"
                              placeholder="Escribe comentarios para el aprendiz...">{{ old('observacion') }}</textarea>
                </div>
                <button type="submit" class="btn btn-sena text-white w-100">
                    <i class="bi bi-send me-2"></i>Enviar Revisión
                </button>
            </form>
        </div>
    </div>
    @elseif($entrega->estado !== 'en_revision')
    <div class="col-lg-5">
        <div class="table-card p-4 text-center">
            @if($entrega->estado === 'aprobada')
            <i class="bi bi-check-circle-fill text-success" style="font-size:48px;"></i>
            <h6 class="mt-3 fw-bold text-success">Evidencia Aprobada</h6>
            @else
            <i class="bi bi-x-circle-fill text-danger" style="font-size:48px;"></i>
            <h6 class="mt-3 fw-bold text-danger">Evidencia Rechazada</h6>
            @endif
            @if($entrega->revisado_por)
            <p class="text-muted mt-2" style="font-size:13px;">
                Revisada por <strong>{{ $entrega->revisor->name }}</strong><br>
                el {{ $entrega->fecha_revision->format('d/m/Y H:i') }}
            </p>
            @endif
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.querySelectorAll('input[name="estado"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('optAprobada').style.borderColor = '#e5e7eb';
        document.getElementById('optRechazada').style.borderColor = '#e5e7eb';
        if (this.value === 'aprobada') {
            document.getElementById('optAprobada').style.borderColor = '#22c55e';
        } else {
            document.getElementById('optRechazada').style.borderColor = '#dc2626';
        }
    });
});
</script>
@endpush
@endsection
