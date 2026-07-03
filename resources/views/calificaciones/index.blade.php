@extends('layouts.app')
@section('title','Calificaciones')
@section('page-title','Calificaciones')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-123 text-success me-2"></i>Calificaciones</h4>
    @if(!Auth::user()->esAprendiz())
    <a href="{{ route('calificaciones.porActividad') }}" class="btn btn-sena"><i class="bi bi-pencil-square me-1"></i>Registrar Notas</a>
    @endif
</div>

@if(Auth::user()->esAprendiz())
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center border-0" style="background:linear-gradient(135deg,#39A900,#1a4f00);color:#fff;">
            <div class="card-body py-4">
                <div style="font-size:2.5rem;font-weight:700;">{{ number_format($definitiva,1) }}</div>
                <div class="mt-1 opacity-75">Definitiva Final</div>
                <div class="mt-2">
                    <span class="badge bg-white text-success fw-bold px-3 py-2">
                        @if($definitiva >= 6) APROBADO @else EN PROCESO @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header fw-semibold">Mis Notas por Actividad</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Actividad</th><th>Nota</th><th>Instructor</th><th>Observación</th></tr></thead>
                    <tbody>
                    @forelse($calificaciones as $cal)
                    <tr>
                        <td>{{ $cal->actividad->titulo }}</td>
                        <td><span class="badge bg-{{ $cal->color_nota }} fs-6">{{ $cal->nota_formateada }}</span></td>
                        <td>{{ $cal->instructor->name }}</td>
                        <td><small>{{ $cal->observacion ?? '-' }}</small></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">Sin calificaciones aún.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
<div class="card mb-3">
    <div class="card-body py-2">
        <form class="row g-2">
            <div class="col-md-4">
                <select name="ficha" class="form-select form-select-sm">
                    <option value="">Todas las fichas</option>
                    @foreach($fichas as $f)
                    <option value="{{ $f }}" @selected(request('ficha')==$f)>Ficha {{ $f }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-sm btn-secondary w-100">Filtrar</button></div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Aprendiz</th><th>Ficha</th><th>Actividad</th><th>Nota</th><th>Instructor</th><th>Fecha</th></tr>
                </thead>
                <tbody>
                @forelse($calificaciones as $cal)
                <tr>
                    <td>{{ $cal->aprendiz->user->name }}</td>
                    <td><span class="badge bg-secondary">{{ $cal->aprendiz->ficha }}</span></td>
                    <td>{{ $cal->actividad->titulo }}</td>
                    <td><span class="badge bg-{{ $cal->color_nota }} fs-6">{{ $cal->nota_formateada }}</span></td>
                    <td>{{ $cal->instructor->name }}</td>
                    <td>{{ $cal->updated_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No hay calificaciones.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $calificaciones->links() }}</div>
@endif
@endsection
