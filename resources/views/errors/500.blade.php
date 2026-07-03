@extends('layouts.app')
@section('title', 'Error del Servidor')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center py-5 text-center">
    <div style="font-size:100px;font-weight:900;color:#e2e8f0;line-height:1;">500</div>
    <h4 class="mt-2 mb-2 fw-bold" style="color:#dc2626;">Error del Servidor</h4>
    <p class="text-muted mb-4" style="max-width:400px;">
        Ocurrió un error inesperado. Por favor intenta de nuevo o contacta al administrador.
    </p>
    <a href="{{ route('dashboard') }}" class="btn btn-sena text-white px-5">
        <i class="bi bi-house me-2"></i>Ir al Dashboard
    </a>
</div>
@endsection
