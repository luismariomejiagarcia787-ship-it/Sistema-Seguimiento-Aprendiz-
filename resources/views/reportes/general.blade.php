@extends('layouts.app')
@section('title', 'Reporte General')
@section('breadcrumb', 'Reportes > General')

@section('content')
<div class="page-header d-flex justify-content-between">
    <div>
        <h4><i class="bi bi-bar-chart-fill me-2" style="color:#0891b2;"></i>Reporte General por Ficha</h4>
        @if($fichaSeleccionada)
        <p>Ficha: <strong>{{ $fichaSeleccionada }}</strong> — Generado el {{ now()->format('d/m/Y H:i') }}</p>
        @else
        <p>Selecciona una ficha para generar el reporte</p>
        @endif
    </div>
    <div class="d-flex gap-2">
        @if($fichaSeleccionada)
        <button onclick="window.print()" class="btn btn-outline-primary">
            <i class="bi bi-printer me-1"></i>Imprimir / PDF
        </button>
        @endif
        <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>
</div>

{{-- SELECTOR DE FICHA --}}
<div class="table-card mb-4 p-3">
    <form method="GET" action="{{ route('reportes.general') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold">Seleccionar Ficha *</label>
            <select class="form-select" name="ficha" required>
                <option value="">— Selecciona una ficha —</option>
                @foreach($fichasDisponibles as $f)
                <option value="{{ $f }}" {{ $fichaSeleccionada==$f?'selected':'' }}>Ficha {{ $f }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-sena text-white">
                <i class="bi bi-bar-chart me-1"></i>Generar Reporte
            </button>
        </div>
    </form>
</div>

@if($fichaSeleccionada && $stats)
{{-- RESUMEN ESTADÍSTICO --}}
<div class="row g-3 mb-4">
    @php
    $cards = [
        ['val' => $stats['total_aprendices'],    'label' => 'Total Aprendices',     'icon' => 'bi-people-fill',       'color' => '#39A900'],
        ['val' => $stats['aprendices_activos'],  'label' => 'Activos',              'icon' => 'bi-person-check-fill', 'color' => '#22c55e'],
        ['val' => $stats['total_actividades'],   'label' => 'Actividades',          'icon' => 'bi-journal-check',     'color' => '#ca8a04'],
        ['val' => $stats['total_entregas'],      'label' => 'Evidencias',           'icon' => 'bi-folder2-open',      'color' => '#dc2626'],
        ['val' => $stats['entregas_aprobadas'],  'label' => 'Aprobadas',            'icon' => 'bi-patch-check',       'color' => '#22c55e'],
        ['val' => $stats['entregas_pendientes'], 'label' => 'En Revisión',          'icon' => 'bi-hourglass-split',   'color' => '#f59e0b'],
        ['val' => $stats['total_seguimientos'],  'label' => 'Seguimientos',         'icon' => 'bi-graph-up',          'color' => '#7c3aed'],
        ['val' => number_format($progreso_promedio, 1).'%', 'label' => 'Avance Promedio', 'icon' => 'bi-speedometer2', 'color' => '#0d6efd'],
    ];
    @endphp
    @foreach($cards as $c)
    <div class="col-6 col-md-3">
        <div class="stat-card text-center py-3">
            <i class="bi {{ $c['icon'] }} fs-3 mb-2" style="color:{{ $c['color'] }};"></i>
            <div class="stat-value" style="font-size:24px;">{{ $c['val'] }}</div>
            <div class="stat-label" style="font-size:12px;">{{ $c['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- TABLA APRENDICES POR PROGRESO --}}
<div class="table-card">
    <div class="card-header">
        <i class="bi bi-people me-2"></i>Progreso por Aprendiz — Ficha {{ $fichaSeleccionada }}
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>#</th><th>Aprendiz</th><th>Programa</th><th>Progreso</th><th>Evidencias</th><th>Estado</th></tr>
            </thead>
            <tbody>
                @forelse($aprendices_progreso as $i => $row)
                <tr>
                    <td style="font-size:13px;color:#6c757d;">{{ $i + 1 }}</td>
                    <td>
                        <div style="font-size:14px;font-weight:600;">{{ $row['aprendiz']->user->name }}</div>
                        <div style="font-size:12px;color:#6c757d;">{{ $row['aprendiz']->user->email }}</div>
                    </td>
                    <td style="font-size:13px;">{{ Str::limit($row['aprendiz']->programa_formacion, 30) }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:6px;max-width:100px;">
                                <div class="progress-bar" style="width:{{ $row['progreso'] }}%;background:{{ $row['progreso'] >= 80 ? '#22c55e' : ($row['progreso'] >= 50 ? '#3b82f6' : '#f59e0b') }};"></div>
                            </div>
                            <span style="font-size:13px;font-weight:600;">{{ number_format($row['progreso'], 1) }}%</span>
                        </div>
                    </td>
                    <td>
                        @php
                            $ents = $row['aprendiz']->entregas;
                            $aprobadas = $ents->where('estado','aprobada')->count();
                            $total = $ents->count();
                        @endphp
                        <span style="font-size:12px;">{{ $aprobadas }}/{{ $total }} aprobadas</span>
                    </td>
                    <td>{!! $row['aprendiz']->estado_badge !!}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Sin aprendices en esta ficha</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

<style>
@media print {
    .sidebar, .main-navbar, .btn, form { display: none !important; }
    .main-content { margin: 0 !important; padding: 10px !important; }
}
</style>
@endsection
