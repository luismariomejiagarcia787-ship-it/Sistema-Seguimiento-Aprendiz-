@extends('layouts.app')
@section('title', 'Nuevo Instructor')
@section('breadcrumb', 'Instructores > Nuevo')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-person-plus-fill me-2" style="color:#0891b2;"></i>Registrar Instructor</h4>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('instructores.store') }}" enctype="multipart/form-data">
                @csrf
                @php $fichasSeleccionadas = old('fichas', []); @endphp
                @include('instructores._form')
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-sena text-white px-4">
                        <i class="bi bi-check-lg me-2"></i>Registrar Instructor
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
