<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Sistema SSA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #1a1a2e, #0f3460); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { border-radius: 20px; border: none; padding: 40px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); max-width: 400px; width: 100%; }
        .form-control { border-radius: 10px; border: 1.5px solid #e2e8f0; padding: 12px 16px; }
        .form-control:focus { border-color: #39A900; box-shadow: 0 0 0 3px rgba(57,169,0,0.15); }
        .btn-green { background: linear-gradient(135deg, #39A900, #2d8700); border: none; border-radius: 10px; padding: 13px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <div class="text-center mb-4">
            <div style="width:55px;height:55px;background:#39A900;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;">
                <i class="bi bi-key-fill text-white fs-4"></i>
            </div>
            <h5 style="font-weight:700;">Recuperar Contraseña</h5>
            <p class="text-muted" style="font-size:13px;">Ingresa tu correo y te enviaremos instrucciones</p>
        </div>
        @if(session('status'))
        <div class="alert border-0" style="background:#d1fae5;color:#065f46;border-radius:10px;">
            <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
        </div>
        @endif
        <form method="POST" action="{{ route('password.email') ?? '#' }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" style="font-size:13px;font-weight:600;">Correo electrónico</label>
                <input type="email" class="form-control" name="email" placeholder="usuario@sena.edu.co" required>
            </div>
            <button type="submit" class="btn btn-green text-white w-100">
                <i class="bi bi-send me-2"></i>Enviar instrucciones
            </button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" style="font-size:13px;color:#39A900;text-decoration:none;">
                <i class="bi bi-arrow-left me-1"></i>Volver al login
            </a>
        </div>
    </div>
</body>
</html>
