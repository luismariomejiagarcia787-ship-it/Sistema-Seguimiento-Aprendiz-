@extends('layouts.app')
@section('title', 'Ficha ' . $ficha->numero)
@section('page-title', 'Ficha ' . $ficha->numero)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-journal-bookmark-fill text-success me-2"></i>Ficha {{ $ficha->numero }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('fichas.edit', $ficha) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil me-1"></i>Editar</a>
        <a href="{{ route('fichas.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">Información</div>
            <div class="card-body">
                <p><strong>Número:</strong> {{ $ficha->numero }}</p>
                <p><strong>Programa:</strong> {{ $ficha->programa_formacion }}</p>
                <p><strong>Inicio:</strong> {{ $ficha->fecha_inicio->format('d/m/Y') }}</p>
                @if($ficha->fecha_fin)<p><strong>Fin:</strong> {{ $ficha->fecha_fin->format('d/m/Y') }}</p>@endif
                <p><strong>Estado:</strong> {!! $ficha->estado_badge !!}</p>
                @if($ficha->descripcion)<p><strong>Descripción:</strong> {{ $ficha->descripcion }}</p>@endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">Instructores Asignados</div>
            <div class="card-body">
                @forelse($instructores as $if)
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ $if->instructor->foto_url }}" class="rounded-circle" width="30" height="30" alt="">
                        <span>{{ $if->instructor->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('fichas.quitarInstructor', $ficha) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <input type="hidden" name="instructor_id" value="{{ $if->instructor->id }}">
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Quitar instructor?')"><i class="bi bi-x"></i></button>
                    </form>
                </div>
                @empty
                <p class="text-muted small">Sin instructores asignados.</p>
                @endforelse
                <hr>
                <form method="POST" action="{{ route('fichas.asignarInstructor', $ficha) }}">
                    @csrf
                    <select name="instructor_id" class="form-select form-select-sm mb-2" required>
                        <option value="">Seleccionar instructor...</option>
                        @foreach(\App\Models\User::where('rol','instructor')->get() as $inst)
                        <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-sena w-100"><i class="bi bi-plus me-1"></i>Asignar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">Aprendices ({{ $aprendices->count() }})</div>
            <div class="card-body p-0" style="max-height:320px; overflow-y:auto;">
                <ul class="list-group list-group-flush">
                @forelse($aprendices as $aprendiz)
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <img src="{{ $aprendiz->user->foto_url }}" class="rounded-circle" width="28" height="28" alt="">
                        <a href="{{ route('aprendices.show', $aprendiz) }}" class="text-decoration-none">{{ $aprendiz->user->name }}</a>
                        <span class="ms-auto">{!! $aprendiz->estado_badge !!}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted text-center">Sin aprendices.</li>
                @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
