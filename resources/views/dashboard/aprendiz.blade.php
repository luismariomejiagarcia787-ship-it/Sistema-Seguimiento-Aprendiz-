@extends('layouts.app')
@section('title','Mi Dashboard')
@section('page-title','Mi Panel')
@section('content')
@if(!$aprendiz)
<div class="alert alert-warning">Tu perfil de aprendiz aún no está configurado. Contacta al administrador.</div>
@else
<div class="mb-3 d-flex align-items-center gap-3">
    <img src="{{ Auth::user()->foto_url }}" class="rounded-circle" width="60" height="60" alt="">
    <div>
        <h5 class="fw-bold mb-0">{{ Auth::user()->name }}</h5>
        <small class="text-muted">Ficha {{ $aprendiz->ficha }} — {{ $aprendiz->programa_formacion }}</small>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center" style="border-top:4px solid #39A900;">
            <div class="card-body"><div class="fs-2 fw-bold text-success">{{ number_format($definitiva,1) }}</div><small class="text-muted">Definitiva</small></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center" style="border-top:4px solid #3b82f6;">
            <div class="card-body"><div class="fs-2 fw-bold text-primary">{{ $calificaciones->count() }}</div><small class="text-muted">Notas</small></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center" style="border-top:4px solid #f59e0b;">
            <div class="card-body"><div class="fs-2 fw-bold text-warning">{{ $actividades_pendientes->count() }}</div><small class="text-muted">Pendientes</small></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center" style="border-top:4px solid #8b5cf6;">
            <div class="card-body"><div class="fs-2 fw-bold" style="color:#8b5cf6;">{{ $observaciones->count() }}</div><small class="text-muted">Observaciones</small></div>
        </div>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span class="fw-semibold">Mis Últimas Notas</span>
                <a href="{{ route('calificaciones.index') }}" class="btn btn-sm btn-outline-secondary">Ver todas</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                    @forelse($calificaciones->take(5) as $cal)
                    <tr>
                        <td>{{ Str::limit($cal->actividad->titulo,30) }}</td>
                        <td class="text-end"><span class="badge bg-{{ $cal->color_nota }}">{{ $cal->nota_formateada }}</span></td>
                    </tr>
                    @empty
                    <tr><td class="text-center text-muted py-2">Sin notas registradas.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold">Actividades Pendientes</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                @forelse($actividades_pendientes->take(5) as $act)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small fw-semibold">{{ $act->titulo }}</div>
                        <small class="text-muted">{{ $act->fecha_limite->format('d/m/Y') }}</small>
                    </div>
                    {!! $act->estado_badge !!}
                </li>
                @empty
                <li class="list-group-item text-center text-muted">Sin pendientes.</li>
                @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
