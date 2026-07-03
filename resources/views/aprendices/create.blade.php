@extends('layouts.app')
@section('title', 'Nuevo Aprendiz')
@section('page-title', 'Nuevo Aprendiz')
@section('content')
<div class="row justify-content-center">
<div class="col-md-9">
<div class="card">
    <div class="card-header bg-success text-white fw-semibold"><i class="bi bi-person-plus me-2"></i>Registrar Aprendiz</div>
    <div class="card-body">
        <form method="POST" action="{{ route('aprendices.store') }}" enctype="multipart/form-data">
            @csrf
            <h6 class="fw-bold text-muted mb-3 text-uppercase" style="font-size:.75rem; letter-spacing:.08em;">Cuenta de Acceso</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre Completo *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Correo Electrónico *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Contraseña *</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Confirmar Contraseña *</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
            <h6 class="fw-bold text-muted mb-3 text-uppercase" style="font-size:.75rem; letter-spacing:.08em;">Datos del Aprendiz</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Documento *</label>
                    <input type="text" name="documento" class="form-control @error('documento') is-invalid @enderror" value="{{ old('documento') }}" required>
                    @error('documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Estado *</label>
                    <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                        <option value="activo" @selected(old('estado','activo')=='activo')>Activo</option>
                        <option value="inactivo" @selected(old('estado')=='inactivo')>Inactivo</option>
                        <option value="egresado" @selected(old('estado')=='egresado')>Egresado</option>
                        <option value="retirado" @selected(old('estado')=='retirado')>Retirado</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Programa de Formación *</label>
                    <input type="text" name="programa_formacion" class="form-control @error('programa_formacion') is-invalid @enderror" value="{{ old('programa_formacion') }}" required>
                    @error('programa_formacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Número de Ficha *</label>
                    <select name="ficha" class="form-select @error('ficha') is-invalid @enderror" required>
                        <option value="">Seleccionar ficha...</option>
                        @foreach($fichas as $f)
                        <option value="{{ $f->numero }}" @selected(old('ficha')==$f->numero)>{{ $f->numero }} — {{ Str::limit($f->programa_formacion,25) }}</option>
                        @endforeach
                    </select>
                    @error('ficha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror" value="{{ old('fecha_inicio') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Foto de Perfil</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones') }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-sena"><i class="bi bi-save me-1"></i>Registrar Aprendiz</button>
                <a href="{{ route('aprendices.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
