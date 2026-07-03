@extends('layouts.app')
@section('title', 'Editar Aprendiz')
@section('page-title', 'Editar Aprendiz')
@section('content')
<div class="row justify-content-center">
<div class="col-md-9">
<div class="card">
    <div class="card-header bg-warning fw-semibold"><i class="bi bi-pencil me-2"></i>Editar Aprendiz — {{ $aprendiz->user->name }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('aprendices.update', $aprendiz) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <h6 class="fw-bold text-muted mb-3 text-uppercase" style="font-size:.75rem; letter-spacing:.08em;">Cuenta de Acceso</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre Completo *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $aprendiz->user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Correo Electrónico *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $aprendiz->user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nueva Contraseña <small class="text-muted">(dejar vacío para no cambiar)</small></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            <h6 class="fw-bold text-muted mb-3 text-uppercase" style="font-size:.75rem; letter-spacing:.08em;">Datos del Aprendiz</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Documento *</label>
                    <input type="text" name="documento" class="form-control @error('documento') is-invalid @enderror" value="{{ old('documento', $aprendiz->documento) }}" required>
                    @error('documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $aprendiz->telefono) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Estado *</label>
                    <select name="estado" class="form-select" required>
                        @foreach(['activo','inactivo','egresado','retirado'] as $est)
                        <option value="{{ $est }}" @selected(old('estado',$aprendiz->estado)==$est)>{{ ucfirst($est) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Programa de Formación *</label>
                    <input type="text" name="programa_formacion" class="form-control" value="{{ old('programa_formacion', $aprendiz->programa_formacion) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Número de Ficha *</label>
                    <select name="ficha" class="form-select" required>
                        <option value="">Seleccionar ficha...</option>
                        @foreach($fichas as $f)
                        <option value="{{ $f->numero }}" @selected(old('ficha',$aprendiz->ficha)==$f->numero)>{{ $f->numero }} — {{ Str::limit($f->programa_formacion,25) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', $aprendiz->fecha_inicio->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Foto de Perfil</label>
                    @if($aprendiz->user->foto)
                    <div class="mb-1"><img src="{{ $aprendiz->user->foto_url }}" class="rounded-circle" width="40" height="40" alt=""> <small class="text-muted">Actual</small></div>
                    @endif
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $aprendiz->observaciones) }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-sena"><i class="bi bi-save me-1"></i>Actualizar</button>
                <a href="{{ route('aprendices.show', $aprendiz) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
