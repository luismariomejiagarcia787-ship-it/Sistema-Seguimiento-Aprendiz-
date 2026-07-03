@extends('layouts.app')
@section('title', 'Evidencias')
@section('breadcrumb', 'Evidencias')

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h4><i class="bi bi-folder2-open me-2" style="color:#dc2626;"></i>Evidencias</h4>
        <p>Gestión de archivos y evidencias de aprendizaje</p>
    </div>
    @if(auth()->user()->esAprendiz())
    <a href="{{ route('evidencias.create') }}" class="btn btn-sena text-white">
        <i class="bi bi-upload me-2"></i>Subir Evidencia
    </a>
    @endif
</div>

<!-- FILTROS -->
<div class="table-card mb-4 p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado">
                <option value="">Todos los estados</option>
                <option value="en_revision" {{ request('estado')=='en_revision'?'selected':'' }}>En Revisión</option>
                <option value="aprobada"    {{ request('estado')=='aprobada'?'selected':'' }}>Aprobada</option>
                <option value="rechazada"   {{ request('estado')=='rechazada'?'selected':'' }}>Rechazada</option>
            </select>
        </div>

        @if(!auth()->user()->esAprendiz() && !empty($fichas))
        <div class="col-md-3">
            <label class="form-label">Ficha</label>
            <select class="form-select" name="ficha">
                <option value="">Todas las fichas</option>
                @foreach($fichas as $f)
                <option value="{{ $f }}" {{ request('ficha')==$f?'selected':'' }}>{{ $f }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-sena text-white flex-fill"><i class="bi bi-funnel me-1"></i>Filtrar</button>
            <a href="{{ route('evidencias.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="card-header">Evidencias ({{ $entregas->total() }})</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Aprendiz</th>
                    <th>Ficha</th>
                    <th>Actividad</th>
                    <th>Archivo</th>
                    <th>Fecha Envío</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entregas as $entrega)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $entrega->aprendiz->user->foto_url }}" class="avatar-sm" alt="">
                            <span style="font-size:13px;">{{ $entrega->aprendiz->user->name }}</span>
                        </div>
                    </td>
                    <td><span class="badge" style="background:#ede9fe;color:#5b21b6;">{{ $entrega->aprendiz->ficha }}</span></td>
                    <td style="font-size:13px;">{{ Str::limit($entrega->actividad->titulo, 30) }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi {{ $entrega->icono_archivo }} fs-5"></i>
                            <span style="font-size:12px;">{{ Str::limit($entrega->nombre_archivo, 22) }}</span>
                        </div>
                    </td>
                    <td style="font-size:12px;color:#6c757d;">{{ $entrega->created_at->format('d/m/Y H:i') }}</td>
                    <td>{!! $entrega->estado_badge !!}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('evidencias.show', $entrega) }}"
                               class="btn btn-sm btn-outline-primary" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('evidencias.download', $entrega) }}"
                               class="btn btn-sm btn-outline-success" title="Descargar">
                                <i class="bi bi-download"></i>
                            </a>
                            @if(auth()->user()->esAdministrador() || auth()->user()->esInstructor())
                            <form method="POST" action="{{ route('evidencias.destroy', $entrega) }}"
                                  onsubmit="return confirm('¿Eliminar evidencia?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-folder2 fs-1 d-block mb-2 opacity-25"></i>
                        No hay evidencias registradas
                        @if(auth()->user()->esAprendiz())
                        <br>
                        <a href="{{ route('evidencias.create') }}" class="btn btn-sena text-white mt-2">
                            <i class="bi bi-upload me-1"></i>Subir tu primera evidencia
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($entregas->hasPages())
    <div class="p-3">{{ $entregas->links() }}</div>
    @endif
</div>
@endsection
