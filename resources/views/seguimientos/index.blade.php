@extends('layouts.app')
@section('title', 'Seguimiento')
@section('breadcrumb', 'Seguimiento')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-graph-up-arrow me-2" style="color:#7c3aed;"></i>Seguimiento de Aprendices</h4>
    <p>Registra y consulta el progreso de cada aprendiz</p>
</div>

<!-- FILTROS -->
<div class="table-card mb-4 p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label">Buscar aprendiz</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" name="buscar"
                       value="{{ request('buscar') }}" placeholder="Nombre del aprendiz...">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label">Ficha</label>
            <select class="form-select" name="ficha">
                <option value="">Todas las fichas</option>
                @foreach($fichas as $f)
                <option value="{{ $f }}" {{ request('ficha')==$f?'selected':'' }}>{{ $f }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-sena text-white flex-fill">
                <i class="bi bi-funnel me-1"></i>Filtrar
            </button>
            <a href="{{ route('seguimientos.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>
    </form>
</div>

<div class="row g-3">
    @forelse($aprendices as $ap)
    @php
        $total    = $ap->actividades()->count();
        $completas = $ap->actividades()->wherePivot('estado','completada')->count();
        $progreso  = $total > 0 ? round(($completas / $total) * 100) : 0;
        $color     = $progreso >= 80 ? '#22c55e' : ($progreso >= 50 ? '#3b82f6' : '#f59e0b');
    @endphp
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="{{ $ap->user->foto_url }}" class="rounded-circle"
                     style="width:52px;height:52px;object-fit:cover;border:2px solid {{ $color }};">
                <div>
                    <div class="fw-bold" style="font-size:14px;">{{ $ap->user->name }}</div>
                    <div style="font-size:12px;color:#6c757d;">Ficha {{ $ap->ficha }}</div>
                    <div style="font-size:11px;color:#6c757d;">{{ Str::limit($ap->programa_formacion, 30) }}</div>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-1">
                <span style="font-size:12px;color:#6c757d;">Progreso</span>
                <span style="font-size:13px;font-weight:700;color:{{ $color }};">{{ $progreso }}%</span>
            </div>
            <div class="progress mb-3" style="height:8px;">
                <div class="progress-bar" style="width:{{ $progreso }}%;background:{{ $color }};border-radius:10px;"></div>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <div class="text-center">
                    <div style="font-size:18px;font-weight:700;">{{ $ap->seguimientos->count() }}</div>
                    <div style="font-size:11px;color:#6c757d;">Seguimientos</div>
                </div>
                <div class="text-center">
                    <div style="font-size:18px;font-weight:700;">{{ $total }}</div>
                    <div style="font-size:11px;color:#6c757d;">Actividades</div>
                </div>
                <div class="text-center">
                    <div style="font-size:18px;font-weight:700;color:#22c55e;">{{ $completas }}</div>
                    <div style="font-size:11px;color:#6c757d;">Completadas</div>
                </div>
            </div>

            <a href="{{ route('seguimientos.show', $ap) }}" class="btn btn-sm w-100 text-white fw-semibold"
               style="background:#7c3aed;">
                <i class="bi bi-person-lines-fill me-2"></i>Ver / Registrar Seguimiento
            </a>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="table-card text-center py-5 text-muted">
            <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
            <p>No hay aprendices activos para mostrar.</p>
        </div>
    </div>
    @endforelse
</div>

@if($aprendices->hasPages())
<div class="mt-3">{{ $aprendices->links() }}</div>
@endif
@endsection
