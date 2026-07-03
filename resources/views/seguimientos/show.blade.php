@extends('layouts.app')
@section('title', 'Seguimiento: ' . $aprendiz->user->name)
@section('breadcrumb', 'Seguimiento > Detalle')

@section('content')
<div class="page-header d-flex justify-content-between">
    <div>
        <h4><i class="bi bi-person-lines-fill me-2" style="color:#7c3aed;"></i>
            Seguimiento: {{ $aprendiz->user->name }}
        </h4>
        <p>{{ $aprendiz->programa_formacion }} &nbsp;·&nbsp; Ficha: <strong>{{ $aprendiz->ficha }}</strong></p>
    </div>
    <a href="{{ route('seguimientos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="row g-4">
    <!-- COL IZQUIERDA: perfil + registrar -->
    <div class="col-lg-4">
        <!-- Perfil y progreso -->
        <div class="stat-card text-center mb-4">
            <img src="{{ $aprendiz->user->foto_url }}" class="rounded-circle mb-3"
                 style="width:84px;height:84px;object-fit:cover;border:3px solid #7c3aed;">
            <h5 class="fw-bold mb-0">{{ $aprendiz->user->name }}</h5>
            <p class="text-muted mb-3" style="font-size:13px;">{{ $aprendiz->user->email }}</p>
            <div style="font-size:46px;font-weight:800;
                color:{{ $progreso >= 80 ? '#22c55e' : ($progreso >= 50 ? '#3b82f6' : '#f59e0b') }};">
                {{ number_format($progreso, 1) }}%
            </div>
            <div class="progress my-2" style="height:10px;">
                <div class="progress-bar" style="width:{{ $progreso }}%;
                    background:{{ $progreso >= 80 ? '#22c55e' : ($progreso >= 50 ? '#3b82f6' : '#f59e0b') }};"></div>
            </div>
            <p class="text-muted mb-3" style="font-size:12px;">Progreso calculado automáticamente</p>
            <div class="row g-2">
                <div class="col-6">
                    <div class="p-2 rounded-3" style="background:#f8f9fa;">
                        <div style="font-size:20px;font-weight:700;">{{ $aprendiz->actividades->count() }}</div>
                        <div style="font-size:11px;color:#6c757d;">Actividades</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 rounded-3" style="background:#d1fae5;">
                        <div style="font-size:20px;font-weight:700;color:#065f46;">
                            {{ $aprendiz->actividades->where('pivot.estado','completada')->count() }}
                        </div>
                        <div style="font-size:11px;color:#065f46;">Completadas</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario nuevo seguimiento -->
        <div class="table-card p-4">
            <h6 class="fw-semibold mb-3"><i class="bi bi-plus-circle me-2" style="color:#7c3aed;"></i>Registrar Seguimiento</h6>
            <form method="POST" action="{{ route('seguimientos.store', $aprendiz) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Fecha *</label>
                    <input type="date" class="form-control @error('fecha_seguimiento') is-invalid @enderror"
                           name="fecha_seguimiento"
                           value="{{ old('fecha_seguimiento', now()->format('Y-m-d')) }}" required>
                    @error('fecha_seguimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Observación *</label>
                    <textarea class="form-control @error('comentario') is-invalid @enderror"
                              name="comentario" rows="5"
                              placeholder="Describe el avance del aprendiz..."
                              required>{{ old('comentario') }}</textarea>
                    @error('comentario')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="p-3 mb-3 rounded-3 text-center" style="background:#f3f0ff;">
                    <div style="font-size:11px;color:#7c3aed;font-weight:600;">Progreso que se registrará</div>
                    <div style="font-size:26px;font-weight:800;color:#7c3aed;">{{ number_format($progreso, 1) }}%</div>
                </div>
                <button type="submit" class="btn w-100 text-white fw-semibold" style="background:#7c3aed;">
                    <i class="bi bi-save me-2"></i>Guardar Seguimiento
                </button>
            </form>
        </div>
    </div>

    <!-- COL DERECHA: historial -->
    <div class="col-lg-8">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Historial de Seguimientos ({{ $historial->count() }})</span>
            </div>
            <div class="p-3">
                @forelse($historial as $seg)
                <div class="p-4 mb-3 rounded-3" style="background:#fafafa;border:1px solid #e5e7eb;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $seg->instructor->foto_url }}" class="avatar-sm" alt="">
                            <div>
                                <div style="font-size:13px;font-weight:700;">{{ $seg->instructor->name }}</div>
                                <div style="font-size:11px;color:#6c757d;">
                                    {{ $seg->fecha_seguimiento->format('d \d\e F Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#ede9fe;color:#5b21b6;font-size:13px;padding:6px 12px;">
                                {{ $seg->porcentaje }}%
                            </span>
                            {{-- Admin puede eliminar cualquiera; instructor solo los suyos --}}
                            @if(auth()->user()->esAdministrador() ||
                                (auth()->user()->esInstructor() && $seg->instructor_id === auth()->id()))
                            <form method="POST" action="{{ route('seguimientos.destroy', $seg) }}"
                                  onsubmit="return confirm('¿Eliminar este seguimiento?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" style="padding:3px 8px;" title="Eliminar">
                                    <i class="bi bi-trash" style="font-size:12px;"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    <p style="font-size:14px;color:#374151;margin:0;line-height:1.7;">{{ $seg->comentario }}</p>
                    <div class="progress mt-3" style="height:4px;">
                        <div class="progress-bar" style="width:{{ $seg->porcentaje }}%;background:#7c3aed;"></div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-journal-text fs-1 d-block mb-2 opacity-25"></i>
                    <p class="mb-0">Sin seguimientos registrados aún.</p>
                    <small>Usa el formulario de la izquierda para registrar el primer seguimiento.</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
