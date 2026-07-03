@extends('layouts.app')
@section('title','Reportes')
@section('page-title','Reportes')
@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-bar-chart-fill text-success me-2"></i>Reportes del Sistema</h4>
<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center py-5">
                <i class="bi bi-journal-bookmark-fill text-success" style="font-size:3rem;"></i>
                <h5 class="mt-3 fw-bold">Reporte por Ficha</h5>
                <p class="text-muted">Estadísticas completas, definitivas, índice integral y ranking de una ficha de formación.</p>
                <a href="{{ route('reportes.porFicha') }}" class="btn btn-sena">Ver Reporte por Ficha</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body py-5">
                <i class="bi bi-person-lines-fill text-primary" style="font-size:3rem;"></i>
                <h5 class="mt-3 fw-bold">Reporte Individual</h5>
                <p class="text-muted">Hoja de vida completa de un aprendiz: notas, índice integral, observaciones.</p>
                <form method="GET" action="{{ route('reportes.individual') }}" class="d-flex gap-2">
                    <select name="aprendiz_id" class="form-select" required>
                        <option value="">Seleccionar aprendiz...</option>
                        @foreach(\App\Models\Aprendiz::with('user')->get() as $ap)
                        <option value="{{ $ap->id }}">{{ $ap->user->name }} — Ficha {{ $ap->ficha }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary">Ver</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
