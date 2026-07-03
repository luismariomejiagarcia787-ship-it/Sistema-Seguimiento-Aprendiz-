@extends('layouts.app')
@section('title', 'Fichas')
@section('page-title', 'Gestión de Fichas')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-journal-bookmark-fill text-success me-2"></i>Fichas de Formación</h4>
    <a href="{{ route('fichas.create') }}" class="btn btn-sena"><i class="bi bi-plus-lg me-1"></i>Nueva Ficha</a>
</div>
<div class="card mb-3">
    <div class="card-body py-2">
        <form class="row g-2">
            <div class="col-md-5"><input type="text" name="buscar" class="form-control form-control-sm" placeholder="Buscar por número o programa..." value="{{ request('buscar') }}"></div>
            <div class="col-md-3">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="activo" @selected(request('estado')=='activo')>Activo</option>
                    <option value="inactivo" @selected(request('estado')=='inactivo')>Inactivo</option>
                    <option value="terminado" @selected(request('estado')=='terminado')>Terminado</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-sm btn-secondary w-100">Filtrar</button></div>
            <div class="col-md-2"><a href="{{ route('fichas.index') }}" class="btn btn-sm btn-outline-secondary w-100">Limpiar</a></div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Número</th><th>Programa</th><th>Inicio</th><th>Estado</th><th>Aprendices</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($fichas as $ficha)
                <tr>
                    <td><span class="fw-bold text-success">{{ $ficha->numero }}</span></td>
                    <td>{{ $ficha->programa_formacion }}</td>
                    <td>{{ $ficha->fecha_inicio->format('d/m/Y') }}</td>
                    <td>{!! $ficha->estado_badge !!}</td>
                    <td><span class="badge bg-primary">{{ $ficha->aprendices()->count() }}</span></td>
                    <td>
                        <a href="{{ route('fichas.show', $ficha) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('fichas.edit', $ficha) }}" class="btn btn-sm btn-outline-warning me-1"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('fichas.destroy', $ficha) }}" class="d-inline" onsubmit="return confirm('¿Eliminar ficha {{ $ficha->numero }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No hay fichas registradas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $fichas->links() }}</div>
@endsection
