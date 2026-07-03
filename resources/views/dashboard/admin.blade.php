@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Panel de Administrador')
@section('content')
<div class="row g-3 mb-4">
    @php
    $cards = [
        ['label'=>'Aprendices Activos','value'=>$stats['aprendices_activos'],'total'=>$stats['total_aprendices'],'icon'=>'people-fill','color'=>'#39A900'],
        ['label'=>'Instructores','value'=>$stats['total_instructores'],'icon'=>'person-badge-fill','color'=>'#3b82f6'],
        ['label'=>'Fichas','value'=>$stats['total_fichas'],'icon'=>'journal-bookmark-fill','color'=>'#f59e0b'],
        ['label'=>'Actividades','value'=>$stats['total_actividades'],'icon'=>'card-checklist','color'=>'#8b5cf6'],
    ];
    @endphp
    @foreach($cards as $c)
    <div class="col-6 col-md-3">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:52px;height:52px;background:{{ $c['color'] }}20;">
                    <i class="bi bi-{{ $c['icon'] }}" style="font-size:1.5rem;color:{{ $c['color'] }};"></i>
                </div>
                <div>
                    <div style="font-size:1.6rem;font-weight:700;line-height:1;">{{ $c['value'] }}</div>
                    <div class="text-muted" style="font-size:.8rem;">{{ $c['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold">Últimos Aprendices</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                @foreach($aprendices_recientes as $ap)
                <li class="list-group-item d-flex align-items-center gap-3">
                    <img src="{{ $ap->user->foto_url }}" class="rounded-circle" width="36" height="36" alt="">
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $ap->user->name }}</div>
                        <small class="text-muted">Ficha {{ $ap->ficha }}</small>
                    </div>
                    {!! $ap->estado_badge !!}
                    <a href="{{ route('aprendices.show', $ap) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                </li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold">Fichas Recientes</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                @foreach($fichas_resumen as $ficha)
                <li class="list-group-item d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-semibold text-success">{{ $ficha->numero }}</span>
                        <div class="text-muted small">{{ Str::limit($ficha->programa_formacion, 40) }}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary">{{ $ficha->aprendices_count }} aprendices</span>
                        {!! $ficha->estado_badge !!}
                    </div>
                </li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
