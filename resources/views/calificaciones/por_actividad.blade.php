@extends('layouts.app')
@section('title','Registrar Calificaciones')
@section('page-title','Registrar Calificaciones')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square text-success me-2"></i>Notas por Actividad</h4>
    <a href="{{ route('calificaciones.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-9">
                <label class="form-label fw-semibold">Seleccionar Actividad</label>
                <select name="actividad_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Seleccionar actividad --</option>
                    @foreach($actividades as $act)
                    <option value="{{ $act->id }}" @selected(request('actividad_id')==$act->id)>
                        {{ $act->titulo }} @if($act->ficha_asignada) — Ficha {{ $act->ficha_asignada }} @endif
                    </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>
@if($actividad)
<div class="card">
    <div class="card-header fw-semibold">
        Notas: <strong>{{ $actividad->titulo }}</strong>
        <span class="ms-2 text-muted small">({{ $aprendices->count() }} aprendices)</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('calificaciones.guardarPorActividad') }}">
            @csrf
            <input type="hidden" name="actividad_id" value="{{ $actividad->id }}">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr><th>Aprendiz</th><th>Ficha</th><th style="width:140px">Nota (0-10)</th><th>Observación</th><th>Actual</th></tr>
                    </thead>
                    <tbody>
                    @foreach($aprendices as $aprendiz)
                    @php $cal = $calificaciones[$aprendiz->id] ?? null; @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $aprendiz->user->foto_url }}" class="rounded-circle" width="32" height="32" alt="">
                                {{ $aprendiz->user->name }}
                            </div>
                        </td>
                        <td><span class="badge bg-secondary">{{ $aprendiz->ficha }}</span></td>
                        <td>
                            <input type="number" name="notas[{{ $aprendiz->id }}]"
                                   class="form-control form-control-sm"
                                   min="0" max="10" step="0.1"
                                   value="{{ $cal ? $cal->nota : '' }}"
                                   placeholder="0.0">
                        </td>
                        <td>
                            <input type="text" name="observaciones[{{ $aprendiz->id }}]"
                                   class="form-control form-control-sm"
                                   value="{{ $cal?->observacion }}"
                                   placeholder="Opcional">
                        </td>
                        <td>
                            @if($cal)
                                <span class="badge bg-{{ $cal->color_nota }}">{{ $cal->nota_formateada }}</span>
                            @else
                                <span class="badge bg-light text-secondary">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-sena"><i class="bi bi-save me-1"></i>Guardar Calificaciones</button>
        </form>
    </div>
</div>
@endif
@endsection
