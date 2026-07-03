<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSA - @yield('title', 'Sistema de Seguimiento al Aprendiz')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --sena-green: #39A900; --sena-dark: #1a4f00; }
        body { background: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 260px; background: linear-gradient(180deg, var(--sena-dark) 0%, var(--sena-green) 100%); min-height: 100vh; }
        .sidebar .nav-link { color: rgba(255,255,255,.85); padding: .6rem 1.2rem; border-radius: .5rem; margin: 2px 8px; transition: all .2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,.18); color: #fff; }
        .sidebar .nav-link i { width: 22px; }
        .sidebar-brand { color: #fff; font-size: 1.1rem; font-weight: 700; padding: 1.2rem; border-bottom: 1px solid rgba(255,255,255,.15); }
        .main-content { flex: 1; overflow-x: hidden; }
        .navbar-top { background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        .page-header { background: #fff; border-bottom: 2px solid var(--sena-green); padding: 1rem 1.5rem; margin-bottom: 1.5rem; }
        .card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,.07); border-radius: .75rem; }
        .card-header { border-radius: .75rem .75rem 0 0 !important; }
        .btn-sena { background: var(--sena-green); color: #fff; }
        .btn-sena:hover { background: var(--sena-dark); color: #fff; }
        .progress { border-radius: 10px; }
        .badge-role { font-size: .7rem; }
        .table th { font-size: .82rem; text-transform: uppercase; letter-spacing: .03em; color: #6b7280; }
        @media(max-width:768px) { .sidebar { display: none; } }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex">
    {{-- Sidebar --}}
    <nav class="sidebar d-flex flex-column">
        <div class="sidebar-brand d-flex align-items-center gap-2">
            <i class="bi bi-mortarboard-fill fs-4"></i>
            <span>SSA SENA</span>
        </div>
        <ul class="nav flex-column mt-2 flex-grow-1">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            @if(Auth::user()->esAdministrador() || Auth::user()->esInstructor())
            <li class="nav-item">
                <a href="{{ route('aprendices.index') }}" class="nav-link @if(request()->routeIs('aprendices.*')) active @endif">
                    <i class="bi bi-people-fill"></i> Aprendices
                </a>
            </li>
            @endif
            @if(Auth::user()->esAdministrador())
            <li class="nav-item">
                <a href="{{ route('fichas.index') }}" class="nav-link @if(request()->routeIs('fichas.*')) active @endif">
                    <i class="bi bi-journal-bookmark-fill"></i> Fichas
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('instructores.index') }}" class="nav-link @if(request()->routeIs('instructores.*')) active @endif">
                    <i class="bi bi-person-badge-fill"></i> Instructores
                </a>
            </li>
            @endif
            @if(Auth::user()->esAdministrador() || Auth::user()->esInstructor())
            <li class="nav-item">
                <a href="{{ route('actividades.index') }}" class="nav-link @if(request()->routeIs('actividades.*')) active @endif">
                    <i class="bi bi-card-checklist"></i> Actividades
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('calificaciones.index') }}" class="nav-link @if(request()->routeIs('calificaciones.*')) active @endif">
                    <i class="bi bi-123"></i> Calificaciones
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('reportes.index') }}" class="nav-link @if(request()->routeIs('reportes.*')) active @endif">
                    <i class="bi bi-bar-chart-fill"></i> Reportes
                </a>
            </li>
            @endif
            @if(Auth::user()->esAprendiz())
            <li class="nav-item">
                <a href="{{ route('actividades.index') }}" class="nav-link @if(request()->routeIs('actividades.*')) active @endif">
                    <i class="bi bi-card-checklist"></i> Mis Actividades
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('calificaciones.index') }}" class="nav-link @if(request()->routeIs('calificaciones.*')) active @endif">
                    <i class="bi bi-123"></i> Mis Notas
                </a>
            </li>
            @if(Auth::user()->aprendiz)
            <li class="nav-item">
                <a href="{{ route('aprendices.show', Auth::user()->aprendiz) }}" class="nav-link">
                    <i class="bi bi-person-lines-fill"></i> Mi Perfil
                </a>
            </li>
            @endif
            @endif
        </ul>
        <div class="p-3 border-top border-white border-opacity-25">
            <div class="d-flex align-items-center gap-2 mb-2">
                <img src="{{ Auth::user()->foto_url }}" class="rounded-circle" width="34" height="34" alt="">
                <div>
                    <div class="text-white fw-semibold" style="font-size:.82rem; line-height:1.2">{{ Auth::user()->name }}</div>
                    <span class="badge bg-warning text-dark badge-role">{{ ucfirst(Auth::user()->rol) }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-light w-100"><i class="bi bi-box-arrow-right"></i> Salir</button>
            </form>
        </div>
    </nav>

    {{-- Main --}}
    <div class="main-content">
        <nav class="navbar-top d-flex align-items-center justify-content-between px-3 py-2">
            <span class="fw-semibold text-secondary">@yield('page-title', 'Panel')</span>
            <small class="text-muted">{{ now()->format('d/m/Y') }}</small>
        </nav>
        <div class="p-3 p-md-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <strong>Corrige los errores:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
