@extends('layouts.app')
@section('title','Editar Actividad')
@section('page-title','Editar Actividad')
@section('content')
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
    <div class="card-header bg-warning fw-semibold"><i class="bi bi-pencil me-2"></i>Editar Actividad</div>
    <div class="card-body">
        <form method="POST" action="{{ route('actividades.update', $actividad) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Título *</label>
                    <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $actividad->titulo) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $actividad->descripcion) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha Límite *</label>
                    <input type="date" name="fecha_limite" class="form-control" value="{{ old('fecha_limite', $actividad->fecha_limite->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Estado *</label>
                    <select name="estado" class="form-select" required>
                        @foreach(['pendiente','en_proceso','completada','retrasada'] as $est)
                        <option value="{{ $est }}" @selected(old('estado',$actividad->estado)==$est)>{{ ucfirst(str_replace('_',' ',$est)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Peso (%) *</label>
                    <input type="number" name="porcentaje_peso" class="form-control" value="{{ old('porcentaje_peso', $actividad->porcentaje_peso) }}" min="0" max="100" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Ficha Asignada *</label>
                    <select name="ficha_asignada" class="form-select" required>
                        <option value="">Seleccionar ficha...</option>
                        @foreach($fichas as $f)
                        <option value="{{ $f }}" @selected(old('ficha_asignada',$actividad->ficha_asignada)==$f)>Ficha {{ $f }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Si cambia la ficha, se reasignarán los aprendices automáticamente.</small>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-sena"><i class="bi bi-save me-1"></i>Actualizar</button>
                <a href="{{ route('actividades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
