@extends('layouts.app')
@section('title','Actividades')
@section('page-title','Actividades')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-card-checklist text-success me-2"></i>Actividades</h4>
    @if(!Auth::user()->esAprendiz())
    <a href="{{ route('actividades.create') }}" class="btn btn-sena"><i class="bi bi-plus-lg me-1"></i>Nueva Actividad</a>
    @endif
</div>
@if(!Auth::user()->esAprendiz())
<div class="card mb-3">
    <div class="card-body py-2">
        <form class="row g-2">
            <div class="col-md-4"><input type="text" name="buscar" class="form-control form-control-sm" placeholder="Buscar actividad..." value="{{ request('buscar') }}"></div>
            <div class="col-md-2">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    @foreach(['pendiente'=>'Pendiente','en_proceso'=>'En Proceso','completada'=>'Completada','retrasada'=>'Retrasada'] as $k=>$v)
                    <option value="{{ $k }}" @selected(request('estado')==$k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            @if(isset($fichas))
            <div class="col-md-2">
                <select name="ficha" class="form-select form-select-sm">
                    <option value="">Todas las fichas</option>
                    @foreach($fichas as $f)<option value="{{ $f }}" @selected(request('ficha')==$f)>Ficha {{ $f }}</option>@endforeach
                </select>
            </div>
            @endif
            <div class="col-md-2"><button class="btn btn-sm btn-secondary w-100">Filtrar</button></div>
            <div class="col-md-2"><a href="{{ route('actividades.index') }}" class="btn btn-sm btn-outline-secondary w-100">Limpiar</a></div>
        </form>
    </div>
</div>
@endif
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Actividad</th><th>Ficha</th><th>Fecha Límite</th><th>Estado</th>
                    @if(!Auth::user()->esAprendiz())<th>Aprendices</th>@endif
                    <th>Acciones</th></tr>
                </thead>
                <tbody>
                @forelse($actividades as $act)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $act->titulo }}</div>
                        @if(!Auth::user()->esAprendiz())
                        <small class="text-muted">{{ $act->instructor->name }}</small>
                        @endif
                    </td>
                    <td><span class="badge bg-secondary">{{ $act->ficha_asignada ?? '—' }}</span></td>
                    <td>
                        {{ $act->fecha_limite->format('d/m/Y') }}
                        @if($act->estaVencida())<span class="badge bg-danger ms-1" style="font-size:.65rem;">VENCIDA</span>@endif
                    </td>
                    <td>{!! $act->estado_badge !!}</td>
                    @if(!Auth::user()->esAprendiz())
                    <td><span class="badge bg-primary">{{ $act->aprendices()->count() }}</span></td>
                    @endif
                    <td>
                        <a href="{{ route('actividades.show', $act) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-eye"></i></a>
                        @if(!Auth::user()->esAprendiz())
                        <a href="{{ route('actividades.edit', $act) }}" class="btn btn-sm btn-outline-warning me-1"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('actividades.destroy', $act) }}" class="d-inline" onsubmit="return confirm('¿Eliminar actividad?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No hay actividades.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $actividades->links() }}</div>
@endsection
