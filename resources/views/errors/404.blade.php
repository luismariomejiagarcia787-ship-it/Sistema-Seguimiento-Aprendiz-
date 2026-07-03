@extends('layouts.app')
@section('title', 'Página No Encontrada')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center py-5 text-center">
    <div style="font-size:100px;font-weight:900;color:#e2e8f0;line-height:1;">404</div>
    <h4 class="mt-2 mb-2 fw-bold" style="color:#374151;">Página No Encontrada</h4>
    <p class="text-muted mb-4" style="max-width:400px;">
        La página que buscas no existe o fue movida.
    </p>
    <div class="d-flex gap-2">
        <a href="{{ route('dashboard') }}" class="btn btn-sena text-white px-4">
            <i class="bi bi-house me-2"></i>Dashboard
        </a>
        <button onclick="history.back()" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </button>
    </div>
</div>
@endsection
