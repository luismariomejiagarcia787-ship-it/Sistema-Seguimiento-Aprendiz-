@extends('layouts.app')
@section('title','Evaluación Integral')
@section('page-title','Evaluación Integral')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-star-fill text-warning me-2"></i>Evaluación Integral</h4>
    <a href="{{ route('aprendices.show', $aprendiz) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $aprendiz->user->foto_url }}" class="rounded-circle mb-2" width="80" height="80" alt="">
                <h6 class="fw-bold">{{ $aprendiz->user->name }}</h6>
                <p class="text-muted small mb-1">Ficha: {{ $aprendiz->ficha }}</p>
                <p class="text-muted small">{{ $aprendiz->programa_formacion }}</p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i>Cada criterio se evalúa de <strong>0 a 10</strong>. El Índice Integral es el promedio de los 9 criterios.</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header fw-semibold">
                {{ $evaluacion ? 'Actualizar' : 'Registrar' }} Evaluación
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('evaluacion.store', $aprendiz) }}">
                    @csrf
                    <div class="row g-3">
                    @foreach($criterios as $key => $label)
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ $label }} *</label>
                        <div class="input-group">
                            <input type="number" name="{{ $key }}" id="{{ $key }}"
                                   class="form-control @error($key) is-invalid @enderror"
                                   min="0" max="10" step="0.1"
                                   value="{{ old($key, $evaluacion?->$key ?? '') }}"
                                   required
                                   oninput="updateBar('{{ $key }}', this.value)">
                            <span class="input-group-text">/10</span>
                        </div>
                        <div class="progress mt-1" style="height:6px;">
                            <div id="bar_{{ $key }}" class="progress-bar bg-success" style="width:{{ ($evaluacion?->$key ?? 0) * 10 }}%"></div>
                        </div>
                        @error($key)<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    @endforeach
                    <div class="col-12">
                        <label class="form-label fw-semibold">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $evaluacion?->observaciones) }}</textarea>
                    </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-sena"><i class="bi bi-save me-1"></i>Guardar Evaluación</button>
                        <a href="{{ route('aprendices.show', $aprendiz) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function updateBar(key, val) {
    const pct = Math.min(Math.max(parseFloat(val) || 0, 0), 10) * 10;
    document.getElementById('bar_' + key).style.width = pct + '%';
}
</script>
@endpush
