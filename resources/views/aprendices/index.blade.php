@extends('layouts.app')
@section('title', 'Aprendices')
@section('page-title', 'Gestión de Aprendices')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-people-fill text-success me-2"></i>Aprendices</h4>
    @if(Auth::user()->esAdministrador())
    <a href="{{ route('aprendices.create') }}" class="btn btn-sena"><i class="bi bi-plus-lg me-1"></i>Nuevo Aprendiz</a>
    @endif
</div>
<div class="card mb-3">
    <div class="card-body py-2">
        <form class="row g-2">
            <div class="col-md-4"><input type="text" name="buscar" class="form-control form-control-sm" placeholder="Nombre, email o documento..." value="{{ request('buscar') }}"></div>
            <div class="col-md-2">
                <select name="ficha" class="form-select form-select-sm">
                    <option value="">Todas las fichas</option>
                    @foreach($fichas as $f)<option value="{{ $f }}" @selected(request('ficha')==$f)>Ficha {{ $f }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="activo" @selected(request('estado')=='activo')>Activo</option>
                    <option value="inactivo" @selected(request('estado')=='inactivo')>Inactivo</option>
                    <option value="egresado" @selected(request('estado')=='egresado')>Egresado</option>
                    <option value="retirado" @selected(request('estado')=='retirado')>Retirado</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-sm btn-secondary w-100">Filtrar</button></div>
            <div class="col-md-2"><a href="{{ route('aprendices.index') }}" class="btn btn-sm btn-outline-secondary w-100">Limpiar</a></div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Aprendiz</th><th>Documento</th><th>Ficha</th><th>Programa</th><th>Estado</th><th>Definitiva</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                @forelse($aprendices as $aprendiz)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $aprendiz->user->foto_url }}" class="rounded-circle" width="36" height="36" alt="">
                            <div>
                                <div class="fw-semibold">{{ $aprendiz->user->name }}</div>
                                <small class="text-muted">{{ $aprendiz->user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $aprendiz->documento }}</td>
                    <td><span class="badge bg-secondary">{{ $aprendiz->ficha }}</span></td>
                    <td><small>{{ Str::limit($aprendiz->programa_formacion, 35) }}</small></td>
                    <td>{!! $aprendiz->estado_badge !!}</td>
                    <td>
                        @php $def = $aprendiz->calcularDefinitiva(); @endphp
                        @if($def > 0)
                        <span class="badge bg-{{ $def>=8?'success':($def>=6?'primary':($def>=4?'warning':'danger')) }}">{{ number_format($def,1) }}</span>
                        @else
                        <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('aprendices.show', $aprendiz) }}" class="btn btn-sm btn-outline-primary me-1" title="Ver perfil"><i class="bi bi-eye"></i></a>
                        @if(Auth::user()->esAdministrador())
                        <a href="{{ route('aprendices.edit', $aprendiz) }}" class="btn btn-sm btn-outline-warning me-1" title="Editar"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('aprendices.destroy', $aprendiz) }}" class="d-inline" onsubmit="return confirm('¿Eliminar aprendiz {{ $aprendiz->user->name }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No hay aprendices registrados.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $aprendices->links() }}</div>
@endsection
