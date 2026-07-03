@extends('layouts.app')
@section('title', 'Instructores')
@section('breadcrumb', 'Gestión > Instructores')

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h4><i class="bi bi-person-workspace me-2" style="color:#0d6efd;"></i>Instructores</h4>
        <p>Gestión de instructores y asignación de fichas</p>
    </div>
    <a href="{{ route('instructores.create') }}" class="btn btn-sena text-white">
        <i class="bi bi-plus-lg me-2"></i>Nuevo Instructor
    </a>
</div>

<div class="table-card mb-4 p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label">Buscar</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre o email...">
            </div>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-sena text-white flex-fill"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            <a href="{{ route('instructores.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="card-header">Instructores ({{ $instructores->total() }})</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Instructor</th>
                    <th>Teléfono</th>
                    <th>Fichas Asignadas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($instructores as $inst)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $inst->foto_url }}" class="avatar" alt="">
                            <div>
                                <div style="font-size:14px;font-weight:600;">{{ $inst->name }}</div>
                                <div style="font-size:12px;color:#6c757d;">{{ $inst->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $inst->telefono ?? '—' }}</td>
                    <td>
                        @forelse($inst->fichasAsignadas as $fila)
                        <span class="badge me-1" style="background:#ede9fe;color:#5b21b6;">{{ $fila->ficha }}</span>
                        @empty
                        <span class="text-muted" style="font-size:12px;">Sin fichas asignadas</span>
                        @endforelse
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('instructores.show', $inst) }}" class="btn btn-sm btn-outline-primary" title="Ver"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('instructores.edit', $inst) }}" class="btn btn-sm btn-outline-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('instructores.destroy', $inst) }}" onsubmit="return confirm('¿Eliminar instructor?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-5 text-muted">
                    <i class="bi bi-person-workspace fs-1 d-block mb-2 opacity-25"></i>No hay instructores registrados
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($instructores->hasPages())
    <div class="p-3">{{ $instructores->links() }}</div>
    @endif
</div>
@endsection
