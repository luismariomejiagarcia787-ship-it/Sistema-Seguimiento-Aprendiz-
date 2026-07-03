<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema SSA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; padding: 30px 0; }
        .register-card { background: #fff; border-radius: 20px; padding: 40px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); width: 100%; max-width: 520px; margin: 0 auto; }
        .brand-logo { width: 55px; height: 55px; background: #39A900; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; }
        .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e2e8f0; padding: 11px 14px; font-size: 14px; }
        .form-control:focus, .form-select:focus { border-color: #39A900; box-shadow: 0 0 0 3px rgba(57,169,0,0.15); }
        .btn-register { background: linear-gradient(135deg, #39A900, #2d8700); border: none; border-radius: 10px; padding: 13px; font-weight: 600; }
        .form-label { font-size: 13px; font-weight: 600; color: #374151; }
        .aprendiz-fields { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-card">
            <div class="text-center mb-4">
                <div class="brand-logo">
                    <i class="bi bi-person-plus-fill text-white fs-4"></i>
                </div>
                <h4 style="font-weight:700;color:#1a1a2e;">Crear Cuenta</h4>
                <p class="text-muted" style="font-size:13px;">Sistema de Seguimiento al Aprendiz</p>
            </div>

            @if($errors->any())
            <div class="alert border-0 mb-3" style="background:#fee2e2;color:#991b1b;border-radius:10px;">
                <i class="bi bi-exclamation-circle me-2"></i>
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nombre completo *</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Juan Carlos Pérez" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo electrónico *</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" value="{{ old('telefono') }}" placeholder="300 000 0000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña *</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirmar contraseña *</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Rol en el sistema *</label>
                        <select class="form-select" name="rol" id="rolSelect" required>
                            <option value="">Seleccionar rol</option>
                            <option value="administrador" {{ old('rol')=='administrador'?'selected':'' }}>Administrador</option>
                            <option value="instructor" {{ old('rol')=='instructor'?'selected':'' }}>Instructor</option>
                            <option value="aprendiz" {{ old('rol')=='aprendiz'?'selected':'' }}>Aprendiz</option>
                        </select>
                    </div>

                    <!-- Campos solo para aprendiz -->
                    <div class="col-md-6 aprendiz-fields">
                        <label class="form-label">Documento de identidad *</label>
                        <input type="text" class="form-control" name="documento" value="{{ old('documento') }}" placeholder="1234567890">
                    </div>
                    <div class="col-md-6 aprendiz-fields">
                        <label class="form-label">Programa de formación *</label>
                        <input type="text" class="form-control" name="programa_formacion" value="{{ old('programa_formacion') }}" placeholder="Sistemas">
                    </div>
                    <div class="col-md-6 aprendiz-fields">
                        <label class="form-label">Ficha *</label>
                        <input type="text" class="form-control" name="ficha" value="{{ old('ficha') }}" placeholder="2345678">
                    </div>
                </div>
                <button type="submit" class="btn btn-register btn-success w-100 text-white mt-4">
                    <i class="bi bi-person-check me-2"></i>Crear Cuenta
                </button>
            </form>
            <hr class="my-3">
            <p class="text-center mb-0" style="font-size:13px;color:#6b7280;">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}" style="color:#39A900;font-weight:600;text-decoration:none;">Iniciar sesión</a>
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('rolSelect').addEventListener('change', function() {
            const fields = document.querySelectorAll('.aprendiz-fields');
            fields.forEach(f => f.style.display = this.value === 'aprendiz' ? 'block' : 'none');
        });
        // Trigger on load if value is already aprendiz (on error redirect)
        document.getElementById('rolSelect').dispatchEvent(new Event('change'));
    </script>
</body>
</html>
