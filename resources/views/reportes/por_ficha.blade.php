@extends('layouts.app')
@section('title','Reporte por Ficha')
@section('page-title','Reporte por Ficha')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-journal-bookmark-fill text-success me-2"></i>Reporte por Ficha</h4>
    <a href="{{ route('reportes.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Seleccionar Ficha *</label>
                <select name="ficha" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Seleccionar ficha --</option>
                    @foreach($fichas as $f)
                    <option value="{{ $f }}" @selected(isset($fichaNumero) && $fichaNumero==$f)>Ficha {{ $f }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

@if(isset($datos) && $datos !== null)
{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center"><div class="card-body"><div class="fs-3 fw-bold text-success">{{ $stats['total'] }}</div><small class="text-muted">Aprendices</small></div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center"><div class="card-body"><div class="fs-3 fw-bold text-primary">{{ $stats['activos'] }}</div><small class="text-muted">Activos</small></div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center"><div class="card-body"><div class="fs-3 fw-bold text-warning">{{ number_format($stats['promedio_definitiva'],1) }}</div><small class="text-muted">Prom. Definitiva</small></div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center"><div class="card-body"><div class="fs-3 fw-bold text-info">{{ number_format($stats['promedio_integral'],1) }}</div><small class="text-muted">Prom. Integral</small></div></div>
    </div>
</div>

<div class="card">
    <div class="card-header fw-semibold">Aprendices — Ficha {{ $fichaNumero }}</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Aprendiz</th><th>Estado</th><th>Definitiva</th><th>Índice Integral</th><th>Actividades</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                @foreach($datos->values() as $i => $row)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $row['aprendiz']->user->foto_url }}" class="rounded-circle" width="30" height="30" alt="">
                            {{ $row['aprendiz']->user->name }}
                        </div>
                    </td>
                    <td>{!! $row['aprendiz']->estado_badge !!}</td>
                    <td>
                        @php $d=$row['definitiva']; @endphp
                        <span class="badge bg-{{ $d>=8?'success':($d>=6?'primary':($d>=4?'warning':'danger')) }} fs-6">{{ number_format($d,1) }}</span>
                    </td>
                    <td>
                        @php $ii=$row['indice_integral']; @endphp
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-semibold">{{ number_format($ii,1) }}</span>
                            <div class="progress flex-grow-1" style="height:6px;">
                                <div class="progress-bar bg-warning" style="width:{{ $ii*10 }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $row['actividades_calificadas'] }}/{{ $row['total_actividades'] }}</td>
                    <td>
                        <a href="{{ route('aprendices.show', $row['aprendiz']) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
