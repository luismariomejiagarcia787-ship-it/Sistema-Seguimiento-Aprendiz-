<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Acceso Denegado — SSA</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
<div class="text-center">
    <i class="bi bi-shield-x" style="font-size:5rem; color:#39A900;"></i>
    <h2 class="fw-bold mt-3">Acceso Denegado</h2>
    <p class="text-muted">No tienes permiso para acceder a esta sección.</p>
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">Volver</a>
    <a href="{{ route('dashboard') }}" class="btn text-white" style="background:#39A900;">Ir al Dashboard</a>
</div>
</body>
</html>
