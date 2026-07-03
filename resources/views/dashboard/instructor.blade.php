@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Panel del Instructor')
@section('content')
<div class="mb-3"><h5 class="fw-bold">Bienvenido, {{ Auth::user()->name }}</h5></div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center"><div class="card-body"><div class="fs-2 fw-bold text-success">{{ $stats['total_aprendices'] }}</div><small class="text-muted">Mis Aprendices</small></div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center"><div class="card-body"><div class="fs-2 fw-bold text-primary">{{ $stats['fichas_asignadas'] }}</div><small class="text-muted">Fichas</small></div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center"><div class="card-body"><div class="fs-2 fw-bold text-warning">{{ $stats['actividades_creadas'] }}</div><small class="text-muted">Actividades</small></div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center"><div class="card-body"><div class="fs-2 fw-bold text-danger">{{ $stats['calificaciones_pendientes'] }}</div><small class="text-muted">Pend. Calificar</small></div></div>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span class="fw-semibold">Mis Aprendices</span>
                <a href="{{ route('aprendices.index') }}" class="btn btn-sm btn-outline-secondary">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                @forelse($aprendices->take(6) as $ap)
                <li class="list-group-item d-flex align-items-center gap-2">
                    <img src="{{ $ap->user->foto_url }}" class="rounded-circle" width="32" height="32" alt="">
                    <div class="flex-grow-1">
                        <div class="fw-semibold small">{{ $ap->user->name }}</div>
                        <small class="text-muted">Ficha {{ $ap->ficha }}</small>
                    </div>
                    <a href="{{ route('aprendices.show', $ap) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                </li>
                @empty
                <li class="list-group-item text-muted text-center">Sin aprendices asignados.</li>
                @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span class="fw-semibold">Actividades Recientes</span>
                <a href="{{ route('actividades.index') }}" class="btn btn-sm btn-outline-secondary">Ver todas</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                @forelse($actividades_recientes as $act)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold small">{{ $act->titulo }}</div>
                        <small class="text-muted">Vence: {{ $act->fecha_limite->format('d/m/Y') }}</small>
                    </div>
                    {!! $act->estado_badge !!}
                </li>
                @empty
                <li class="list-group-item text-muted text-center">Sin actividades.</li>
                @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
