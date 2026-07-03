<?php $__env->startSection('title', 'Mi Dashboard'); ?>
<?php $__env->startSection('breadcrumb', 'Mi Espacio'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h4><i class="bi bi-mortarboard me-2" style="color:#39A900;"></i>Mi Progreso</h4>
    <p>Hola, <strong><?php echo e(auth()->user()->name); ?></strong> — Ficha: <?php echo e($aprendiz->ficha); ?></p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div style="font-size:52px;font-weight:800;color:<?php echo e($progreso >= 80 ? '#22c55e' : ($progreso >= 50 ? '#3b82f6' : '#f59e0b')); ?>;">
                <?php echo e(number_format($progreso, 1)); ?>%
            </div>
            <div class="stat-label mb-3">Mi Progreso General</div>
            <div class="progress" style="height:12px;">
                <div class="progress-bar" style="width:<?php echo e($progreso); ?>%;background:<?php echo e($progreso >= 80 ? '#22c55e' : ($progreso >= 50 ? '#3b82f6' : '#f59e0b')); ?>;border-radius:10px;"></div>
            </div>
            <small class="text-muted d-block mt-2"><?php echo e($actividades_completadas->count()); ?> de <?php echo e($actividades_completadas->count() + $actividades_pendientes->count()); ?> actividades completadas</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon mb-3" style="background:rgba(234,179,8,0.12);">
                <i class="bi bi-hourglass-split" style="color:#ca8a04;font-size:20px;"></i>
            </div>
            <div class="stat-value"><?php echo e($actividades_pendientes->count()); ?></div>
            <div class="stat-label">Actividades Pendientes</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon mb-3" style="background:rgba(34,197,94,0.12);">
                <i class="bi bi-patch-check-fill" style="color:#22c55e;font-size:20px;"></i>
            </div>
            <div class="stat-value"><?php echo e($actividades_completadas->count()); ?></div>
            <div class="stat-label">Actividades Completadas</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-event me-2"></i>Próximas Fechas Límite</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Actividad</th><th>Fecha Límite</th><th>Estado</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $proximas_fechas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div style="font-size:14px;font-weight:500;"><?php echo e($act->titulo); ?></div>
                                <div style="font-size:12px;color:#6c757d;"><?php echo e(Str::limit($act->descripcion, 50)); ?></div>
                            </td>
                            <td>
                                <span style="font-size:13px;<?php echo e($act->fecha_limite->diffInDays(now()) <= 3 ? 'color:#dc2626;font-weight:600;' : ''); ?>">
                                    <?php echo e($act->fecha_limite->format('d/m/Y')); ?>

                                    <br><small class="text-muted"><?php echo e($act->fecha_limite->diffForHumans()); ?></small>
                                </span>
                            </td>
                            <td><?php echo $act->estado_badge; ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="3" class="text-center py-4 text-muted">
                            <i class="bi bi-check-all fs-3 d-block mb-2"></i>¡No tienes actividades pendientes!
                        </td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="table-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-chat-square-text me-2"></i>Últimas Observaciones</span>
            </div>
            <div class="p-3">
                <?php $__empty_1 = true; $__currentLoopData = $seguimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-3 mb-2 rounded-3" style="background:#f8f9fa;">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:12px;font-weight:600;color:#374151;"><?php echo e($seg->instructor->name); ?></span>
                        <span style="font-size:11px;color:#6c757d;"><?php echo e($seg->fecha_seguimiento->format('d/m/Y')); ?></span>
                    </div>
                    <p style="font-size:13px;margin:0;color:#4b5563;"><?php echo e($seg->comentario); ?></p>
                    <div class="progress mt-2" style="height:4px;">
                        <div class="progress-bar" style="width:<?php echo e($seg->porcentaje); ?>%;background:#39A900;"></div>
                    </div>
                    <small class="text-muted">Progreso en esa fecha: <?php echo e($seg->porcentaje); ?>%</small>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted text-center py-3" style="font-size:13px;">Sin observaciones aún</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="text-center">
            <a href="<?php echo e(route('evidencias.create')); ?>" class="btn btn-sena text-white w-100 py-3" style="border-radius:12px;">
                <i class="bi bi-upload me-2"></i>Subir Nueva Evidencia
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rober\Downloads\sistemamejorado\sistemamejorado\resources\views/dashboard/aprendiz.blade.php ENDPATH**/ ?>