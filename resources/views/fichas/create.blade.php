@extends('layouts.app')
@section('title', 'Nueva Ficha')
@section('page-title', 'Nueva Ficha')
@section('content')
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
    <div class="card-header bg-success text-white"><i class="bi bi-journal-bookmark-fill me-2"></i>Crear Ficha</div>
    <div class="card-body">
        <form method="POST" action="{{ route('fichas.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Número de Ficha *</label>
                    <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" value="{{ old('numero') }}" required>
                    @error('numero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Estado *</label>
                    <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                        <option value="activo" @selected(old('estado','activo')=='activo')>Activo</option>
                        <option value="inactivo" @selected(old('estado')=='inactivo')>Inactivo</option>
                        <option value="terminado" @selected(old('estado')=='terminado')>Terminado</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Programa de Formación *</label>
                    <input type="text" name="programa_formacion" class="form-control @error('programa_formacion') is-invalid @enderror" value="{{ old('programa_formacion') }}" required>
                    @error('programa_formacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror" value="{{ old('fecha_inicio') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-sena"><i class="bi bi-save me-1"></i>Guardar</button>
                <a href="{{ route('fichas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
