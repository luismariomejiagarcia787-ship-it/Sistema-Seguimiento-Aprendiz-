@extends('layouts.app')
@section('title', 'Editar Ficha')
@section('page-title', 'Editar Ficha')
@section('content')
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
    <div class="card-header bg-warning"><i class="bi bi-pencil me-2"></i>Editar Ficha {{ $ficha->numero }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('fichas.update', $ficha) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Número de Ficha *</label>
                    <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" value="{{ old('numero', $ficha->numero) }}" required>
                    @error('numero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Estado *</label>
                    <select name="estado" class="form-select" required>
                        <option value="activo" @selected($ficha->estado=='activo')>Activo</option>
                        <option value="inactivo" @selected($ficha->estado=='inactivo')>Inactivo</option>
                        <option value="terminado" @selected($ficha->estado=='terminado')>Terminado</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Programa de Formación *</label>
                    <input type="text" name="programa_formacion" class="form-control" value="{{ old('programa_formacion', $ficha->programa_formacion) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', $ficha->fecha_inicio->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin', $ficha->fecha_fin?->format('Y-m-d')) }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $ficha->descripcion) }}</textarea>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-sena"><i class="bi bi-save me-1"></i>Actualizar</button>
                <a href="{{ route('fichas.show', $ficha) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
