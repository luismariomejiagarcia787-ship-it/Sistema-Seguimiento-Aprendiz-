@extends('layouts.app')
@section('title', $aprendiz->user->name)
@section('page-title', 'Perfil del Aprendiz')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-person-lines-fill text-success me-2"></i>Hoja de Vida Académica</h4>
    <div class="d-flex gap-2">
        @if(!Auth::user()->esAprendiz())
        <a href="{{ route('evaluacion.create', $aprendiz) }}" class="btn btn-sm btn-warning">
            <i class="bi bi-star me-1"></i>Eval. Integral
        </a>
        @endif
        <a href="{{ route('aprendices.pdf', $aprendiz) }}" class="btn btn-sm btn-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i>Descargar PDF
        </a>
        @if(Auth::user()->esAdministrador())
        <a href="{{ route('aprendices.edit', $aprendiz) }}" class="btn btn-sm btn-outline-warning">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        @endif
        <a href="{{ route('aprendices.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Datos personales --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $aprendiz->user->foto_url }}" class="rounded-circle mb-2" width="90" height="90" alt="">
                <h5 class="fw-bold mb-1">{{ $aprendiz->user->name }}</h5>
                <p class="text-muted small mb-1">{{ $aprendiz->user->email }}</p>
                <p class="text-muted small mb-2">{{ $aprendiz->user->telefono ?? $aprendiz->telefono }}</p>
                {!! $aprendiz->estado_badge !!}
            </div>
            <hr class="my-0">
            <div class="card-body">
                <p class="mb-1"><i class="bi bi-card-text me-2 text-muted"></i><strong>Documento:</strong> {{ $aprendiz->documento }}</p>
                <p class="mb-1"><i class="bi bi-journal-bookmark me-2 text-muted"></i><strong>Ficha:</strong> {{ $aprendiz->ficha }}</p>
                <p class="mb-1"><i class="bi bi-mortarboard me-2 text-muted"></i><strong>Programa:</strong> {{ $aprendiz->programa_formacion }}</p>
                <p class="mb-1"><i class="bi bi-calendar me-2 text-muted"></i><strong>Inicio:</strong> {{ $aprendiz->fecha_inicio->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Resumen académico --}}
    <div class="col-md-8">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="card text-center" style="border-top:4px solid #39A900;">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold text-success">{{ number_format($definitiva,1) }}</div>
                        <small class="text-muted">Definitiva</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center" style="border-top:4px solid #f59e0b;">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold text-warning">{{ number_format($indiceIntegral,1) }}</div>
                        <small class="text-muted">Índice Integral</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center" style="border-top:4px solid #3b82f6;">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold text-primary">{{ $aprendiz->actividades->count() }}</div>
                        <small class="text-muted">Actividades</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center" style="border-top:4px solid #8b5cf6;">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold text-purple" style="color:#8b5cf6">{{ $aprendiz->calificaciones->count() }}</div>
                        <small class="text-muted">Calificadas</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Calificaciones --}}
        <div class="card mt-3">
            <div class="card-header fw-semibold">Calificaciones por Actividad</div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light"><tr><th>Actividad</th><th>Nota</th><th>Instructor</th><th>Observación</th></tr></thead>
                    <tbody>
                    @forelse($aprendiz->calificaciones as $cal)
                    <tr>
                        <td>{{ $cal->actividad->titulo }}</td>
                        <td><span class="badge bg-{{ $cal->color_nota }}">{{ $cal->nota_formateada }}</span></td>
                        <td>{{ $cal->instructor->name }}</td>
                        <td><small class="text-muted">{{ $cal->observacion ?? '—' }}</small></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-2">Sin calificaciones.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Evaluación Integral (H13-H16) --}}
    @if(!empty(array_filter($promediosCriterios)))
    <div class="col-12">
        <div class="card">
            <div class="card-header fw-semibold">
                <i class="bi bi-star-fill text-warning me-2"></i>Evaluación Integral
                <span class="ms-2 badge bg-warning text-dark">Índice: {{ number_format($indiceIntegral,1) }}/10</span>
                <small class="text-muted ms-2">(Promedio de {{ $aprendiz->evaluacionesIntegrales->count() }} evaluador(es))</small>
            </div>
            <div class="card-body">
                <div class="row g-3">
                @foreach($criteriosLabels as $key => $label)
                <div class="col-md-4">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-semibold">{{ $label }}</small>
                        <small class="text-muted">{{ number_format($promediosCriterios[$key] ?? 0, 1) }}/10</small>
                    </div>
                    <div class="progress" style="height:10px;">
                        @php $pct = ($promediosCriterios[$key] ?? 0) * 10; @endphp
                        <div class="progress-bar {{ $pct>=70?'bg-success':($pct>=40?'bg-warning':'bg-danger') }}"
                             style="width:{{ $pct }}%" role="progressbar"></div>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Observaciones (H17) --}}
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header fw-semibold">Observaciones</div>
            <div class="card-body">
                @if(!Auth::user()->esAprendiz())
                <form method="POST" action="{{ route('observaciones.store') }}" class="mb-3">
                    @csrf
                    <input type="hidden" name="aprendiz_id" value="{{ $aprendiz->id }}">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="tipo" class="form-select form-select-sm" required>
                                <option value="general">General</option>
                                <option value="academica">Académica</option>
                                <option value="disciplinaria">Disciplinaria</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group input-group-sm">
                                <textarea name="contenido" class="form-control" rows="1" placeholder="Escribir observación..." required></textarea>
                                <button class="btn btn-sena" type="submit"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                @endif
                @forelse($aprendiz->observaciones as $obs)
                <div class="d-flex gap-2 mb-2">
                    <span class="badge bg-{{ $obs->tipo=='academica'?'primary':($obs->tipo=='disciplinaria'?'danger':'secondary') }} mt-1" style="font-size:.65rem;">{{ ucfirst($obs->tipo) }}</span>
                    <div class="flex-grow-1">
                        <small class="text-muted">{{ $obs->instructor->name }} — {{ $obs->created_at->format('d/m/Y') }}</small>
                        <p class="mb-0 small">{{ $obs->contenido }}</p>
                    </div>
                    @if(!Auth::user()->esAprendiz())
                    <form method="POST" action="{{ route('observaciones.destroy', $obs) }}" onsubmit="return confirm('¿Eliminar?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger border-0 py-0"><i class="bi bi-x"></i></button>
                    </form>
                    @endif
                </div>
                @empty
                <p class="text-muted small">Sin observaciones.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Actividades --}}
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header fw-semibold">Actividades Asignadas</div>
            <div class="card-body p-0" style="max-height:350px;overflow-y:auto;">
                <ul class="list-group list-group-flush">
                @forelse($aprendiz->actividades as $act)
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="fw-semibold">{{ $act->titulo }}</small>
                            <br><small class="text-muted">{{ $act->fecha_limite->format('d/m/Y') }}</small>
                        </div>
                        {!! $act->estado_badge !!}
                    </div>
                </li>
                @empty
                <li class="list-group-item text-muted text-center small">Sin actividades.</li>
                @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
