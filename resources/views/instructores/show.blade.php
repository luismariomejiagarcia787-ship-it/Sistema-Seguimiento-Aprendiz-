@extends('layouts.app')
@section('title', 'Perfil Instructor')
@section('breadcrumb', 'Instructores > Perfil')

@section('content')
<div class="page-header d-flex justify-content-between">
    <div>
        <h4><i class="bi bi-person-workspace me-2" style="color:#0891b2;"></i>Perfil del Instructor</h4>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('instructores.edit', $instructor) }}" class="btn btn-warning text-white">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <form method="POST" action="{{ route('instructores.destroy', $instructor) }}"
              onsubmit="return confirm('¿Eliminar este instructor? Esta acción no se puede deshacer.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash me-1"></i>Eliminar
            </button>
        </form>
        <a href="{{ route('instructores.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="stat-card text-center">
            <img src="{{ $instructor->foto_url }}" class="rounded-circle mb-3"
                 style="width:96px;height:96px;object-fit:cover;border:4px solid #0891b2;">
            <h5 class="fw-bold mb-1">{{ $instructor->name }}</h5>
            <p class="text-muted mb-1" style="font-size:13px;">{{ $instructor->email }}</p>
            @if($instructor->telefono)
            <p class="text-muted mb-3" style="font-size:13px;">
                <i class="bi bi-phone me-1"></i>{{ $instructor->telefono }}
            </p>
            @endif
            <span class="badge" style="background:#dbeafe;color:#1e40af;">Instructor</span>
        </div>

        <div class="stat-card mt-4">
            <h6 class="fw-semibold mb-3"><i class="bi bi-collection me-2"></i>Fichas Asignadas</h6>
            @forelse($instructor->fichasAsignadas as $if)
            <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded-3"
                 style="background:#f0f9ff;">
                <div>
                    <div class="fw-semibold" style="font-size:13px;">Ficha {{ $if->ficha }}</div>
                    <div style="font-size:11px;color:#6c757d;">
                        {{ count($aprendicesFichas[$if->ficha] ?? []) }} aprendice(s)
                    </div>
                </div>
                <i class="bi bi-people text-primary"></i>
            </div>
            @empty
            <p class="text-muted text-center" style="font-size:13px;">Sin fichas asignadas</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-8">
        @foreach($aprendicesFichas as $ficha => $aprendices)
        <div class="table-card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-people-fill me-2"></i>Ficha {{ $ficha }}</span>
                <span class="badge bg-info">{{ $aprendices->count() }} aprendices</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Aprendiz</th>
                            <th>Programa</th>
                            <th>Estado</th>
                            <th>Perfil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aprendices as $ap)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $ap->user->foto_url }}" class="avatar-sm" alt="">
                                    <div>
                                        <div style="font-size:13px;font-weight:600;">{{ $ap->user->name }}</div>
                                        <div style="font-size:11px;color:#6c757d;">{{ $ap->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:12px;">{{ Str::limit($ap->programa_formacion, 30) }}</td>
                            <td>{!! $ap->estado_badge !!}</td>
                            <td>
                                <a href="{{ route('aprendices.show', $ap) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

        @if(empty($aprendicesFichas))
        <div class="table-card p-5 text-center text-muted">
            <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
            <p>No hay aprendices en las fichas asignadas a este instructor.</p>
        </div>
        @endif
    </div>
</div>
@endsection
