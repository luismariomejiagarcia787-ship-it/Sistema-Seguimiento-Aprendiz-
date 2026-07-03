<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — SSA SENA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a4f00 0%, #39A900 100%); min-height: 100vh; display: flex; align-items: center; }
        .card { border: none; border-radius: 1rem; box-shadow: 0 20px 60px rgba(0,0,0,.25); }
        .brand-icon { width: 72px; height: 72px; background: #39A900; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="brand-icon"><i class="bi bi-mortarboard-fill text-white" style="font-size:2rem;"></i></div>
                        <h4 class="fw-bold mb-0">SSA SENA</h4>
                        <p class="text-muted small">Sistema de Seguimiento al Aprendiz</p>
                    </div>
                    <?php if(session('error')): ?>
                    <div class="alert alert-danger py-2"><?php echo e(session('error')); ?></div>
                    <?php endif; ?>
                    <?php if($errors->any()): ?>
                    <div class="alert alert-danger py-2"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('login.post')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required autofocus>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        <button type="submit" class="btn w-100 text-white fw-semibold" style="background:#39A900;">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                        </button>
                    </form>
                    <hr>
                    <div class="text-center">
                        <small class="text-muted">¿No tienes cuenta? <a href="<?php echo e(route('register')); ?>">Regístrate</a></small>
                    </div>
                </div>
            </div>
            <p class="text-center text-white mt-3 small opacity-75">SENA © <?php echo e(date('Y')); ?></p>
        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\Users\PC Lenovo\Downloads\SSA_sistemamejorado\sistemamejorado\resources\views/auth/login.blade.php ENDPATH**/ ?>