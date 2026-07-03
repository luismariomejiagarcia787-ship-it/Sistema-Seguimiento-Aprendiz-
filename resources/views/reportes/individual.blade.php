@extends('layouts.app')
@section('title', 'Reporte — ' . $aprendiz->user->name)
@section('page-title', 'Reporte Individual')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-person-lines-fill text-success me-2"></i>{{ $aprendiz->user->name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('aprendices.pdf', $aprendiz) }}" class="btn btn-sm btn-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i>Descargar PDF
        </a>
        <a href="{{ route('reportes.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $aprendiz->user->foto_url }}" class="rounded-circle mb-2" width="90" height="90" alt="">
                <h5 class="fw-bold mb-1">{{ $aprendiz->user->name }}</h5>
                <p class="text-muted small mb-1">{{ $aprendiz->user->email }}</p>
                {!! $aprendiz->estado_badge !!}
            </div>
            <hr class="my-0">
            <div class="card-body">
                <p class="mb-1 small"><strong>Documento:</strong> {{ $aprendiz->documento }}</p>
                <p class="mb-1 small"><strong>Ficha:</strong> {{ $aprendiz->ficha }}</p>
                <p class="mb-1 small"><strong>Programa:</strong> {{ $aprendiz->programa_formacion }}</p>
                <p class="mb-0 small"><strong>Inicio:</strong> {{ $aprendiz->fecha_inicio->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="row g-3 mb-3">
            <div class="col-6">
                <div class="card text-center" style="border-top:4px solid #39A900;">
                    <div class="card-body py-3">
                        <div class="fs-2 fw-bold text-success">{{ number_format($definitiva,1) }}</div>
                        <small class="text-muted">Definitiva Final</small>
                        <div class="mt-1">
                            <span class="badge bg-{{ $definitiva>=6?'success':'danger' }}">
                                {{ $definitiva>=6?'APROBADO':'EN PROCESO' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card text-center" style="border-top:4px solid #f59e0b;">
                    <div class="card-body py-3">
                        <div class="fs-2 fw-bold text-warning">{{ number_format($indiceIntegral,1) }}</div>
                        <small class="text-muted">Índice Integral</small>
                        <div class="mt-1">
                            <span class="badge bg-warning text-dark">{{ $aprendiz->evaluacionesIntegrales->count() }} eval(s)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-semibold">Calificaciones</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Actividad</th><th>Nota</th><th>Instructor</th></tr></thead>
                    <tbody>
                    @forelse($aprendiz->calificaciones as $cal)
                    <tr>
                        <td>{{ $cal->actividad->titulo }}</td>
                        <td><span class="badge bg-{{ $cal->color_nota }}">{{ $cal->nota_formateada }}</span></td>
                        <td>{{ $cal->instructor->name }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-2">Sin calificaciones.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(!empty(array_filter($promediosCriterios)))
    <div class="col-12">
        <div class="card">
            <div class="card-header fw-semibold">
                <i class="bi bi-star-fill text-warning me-1"></i>Evaluación Integral
                <span class="badge bg-warning text-dark ms-2">Índice: {{ number_format($indiceIntegral,1) }}/10</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                @foreach($criteriosLabels as $key => $label)
                <div class="col-md-4">
                    @php $val = $promediosCriterios[$key] ?? 0; $pct = $val * 10; @endphp
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-semibold">{{ $label }}</small>
                        <small>{{ number_format($val,1) }}/10</small>
                    </div>
                    <div class="progress" style="height:10px;">
                        <div class="progress-bar {{ $pct>=70?'bg-success':($pct>=40?'bg-warning':'bg-danger') }}"
                             style="width:{{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-12">
        <div class="card">
            <div class="card-header fw-semibold">Observaciones</div>
            <div class="card-body">
                @forelse($aprendiz->observaciones as $obs)
                <div class="d-flex gap-2 mb-2 pb-2 border-bottom">
                    <span class="badge bg-{{ $obs->tipo=='academica'?'primary':($obs->tipo=='disciplinaria'?'danger':'secondary') }}" style="font-size:.65rem; height:fit-content;">{{ ucfirst($obs->tipo) }}</span>
                    <div>
                        <small class="text-muted">{{ $obs->instructor->name }} — {{ $obs->created_at->format('d/m/Y') }}</small>
                        <p class="mb-0 small">{{ $obs->contenido }}</p>
                    </div>
                </div>
                @empty
                <p class="text-muted small mb-0">Sin observaciones registradas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
