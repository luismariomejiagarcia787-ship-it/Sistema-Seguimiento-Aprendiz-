@extends('layouts.app')
@section('title', $actividad->titulo)
@section('page-title', 'Detalle de Actividad')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-card-checklist text-success me-2"></i>{{ $actividad->titulo }}</h4>
    <div class="d-flex gap-2">
        @if(!Auth::user()->esAprendiz())
        <a href="{{ route('calificaciones.porActividad') }}?actividad_id={{ $actividad->id }}" class="btn btn-sm btn-sena"><i class="bi bi-pencil-square me-1"></i>Calificar</a>
        <a href="{{ route('actividades.edit', $actividad) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil me-1"></i>Editar</a>
        @endif
        <a href="{{ route('actividades.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header fw-semibold">Información</div>
            <div class="card-body">
                <p><strong>Estado:</strong> {!! $actividad->estado_badge !!}</p>
                <p><strong>Ficha:</strong> <span class="badge bg-secondary">{{ $actividad->ficha_asignada ?? '—' }}</span></p>
                <p><strong>Fecha Límite:</strong> {{ $actividad->fecha_limite->format('d/m/Y') }}</p>
                <p><strong>Peso:</strong> {{ $actividad->porcentaje_peso }}%</p>
                <p><strong>Instructor:</strong> {{ $actividad->instructor->name }}</p>
                @if($actividad->descripcion)
                <p><strong>Descripción:</strong><br><span class="text-muted">{{ $actividad->descripcion }}</span></p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header fw-semibold">Aprendices Asignados ({{ $actividad->aprendices->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Aprendiz</th><th>Ficha</th><th>Nota</th></tr></thead>
                    <tbody>
                    @foreach($actividad->aprendices as $ap)
                    @php $cal = $actividad->calificaciones->firstWhere('aprendiz_id', $ap->id); @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $ap->user->foto_url }}" class="rounded-circle" width="28" height="28" alt="">
                                {{ $ap->user->name }}
                            </div>
                        </td>
                        <td><span class="badge bg-secondary">{{ $ap->ficha }}</span></td>
                        <td>
                            @if($cal)
                                <span class="badge bg-{{ $cal->color_nota }}">{{ $cal->nota_formateada }}</span>
                            @else
                                <span class="badge bg-light text-secondary">Pendiente</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
