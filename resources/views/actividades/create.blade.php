@extends('layouts.app')
@section('title','Nueva Actividad')
@section('page-title','Nueva Actividad')
@section('content')
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
    <div class="card-header bg-success text-white fw-semibold"><i class="bi bi-plus-circle me-2"></i>Crear Actividad</div>
    <div class="card-body">
        <form method="POST" action="{{ route('actividades.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Título *</label>
                    <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo') }}" required>
                    @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha Límite *</label>
                    <input type="date" name="fecha_limite" class="form-control @error('fecha_limite') is-invalid @enderror" value="{{ old('fecha_limite') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Estado *</label>
                    <select name="estado" class="form-select" required>
                        <option value="pendiente" @selected(old('estado','pendiente')=='pendiente')>Pendiente</option>
                        <option value="en_proceso" @selected(old('estado')=='en_proceso')>En Proceso</option>
                        <option value="completada" @selected(old('estado')=='completada')>Completada</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Peso (%) *</label>
                    <input type="number" name="porcentaje_peso" class="form-control @error('porcentaje_peso') is-invalid @enderror" value="{{ old('porcentaje_peso', 10) }}" min="0" max="100" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">
                        Ficha a Asignar *
                        <small class="text-muted fw-normal">(todos los aprendices de esta ficha recibirán la actividad)</small>
                    </label>
                    <select name="ficha_asignada" class="form-select @error('ficha_asignada') is-invalid @enderror" required>
                        <option value="">-- Seleccionar ficha --</option>
                        @foreach($fichas as $f)<option value="{{ $f }}" @selected(old('ficha_asignada')==$f)>Ficha {{ $f }}</option>@endforeach
                    </select>
                    @error('ficha_asignada')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @if(Auth::user()->esAdministrador())
                <div class="col-12">
                    <label class="form-label fw-semibold">Instructor Responsable</label>
                    <select name="instructor_id" class="form-select">
                        <option value="">Seleccionar instructor...</option>
                        @foreach(\App\Models\User::where('rol','instructor')->get() as $inst)
                        <option value="{{ $inst->id }}" @selected(old('instructor_id')==$inst->id)>{{ $inst->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-sena"><i class="bi bi-save me-1"></i>Crear y Asignar</button>
                <a href="{{ route('actividades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
