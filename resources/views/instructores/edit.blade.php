@extends('layouts.app')
@section('title', 'Editar Instructor')
@section('breadcrumb', 'Instructores > Editar')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-pencil-square me-2" style="color:#f59e0b;"></i>Editar Instructor</h4>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('instructores.update', $instructor) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                @php
                    $fichasSeleccionadas = old('fichas', $instructor->fichasAsignadas->pluck('ficha')->toArray());
                @endphp
                @include('instructores._form')
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-warning text-white px-4">
                        <i class="bi bi-check-lg me-2"></i>Actualizar
                    </button>
                    <a href="{{ route('instructores.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
