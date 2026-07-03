<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AprendizController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\ObservacionController;
use App\Http\Controllers\EvaluacionIntegralController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\FichaController;

// ─── Auth ───────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/',                  fn() => redirect()->route('login'));
    Route::get('/login',             [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',            [AuthController::class, 'login'])->name('login.post');
    Route::get('/register',          [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',         [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password',   [AuthController::class, 'showForgotPassword'])->name('password.request');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Fichas (solo admin) H2 ────────────────────────────────
    Route::middleware('role:administrador')->group(function () {
        Route::resource('fichas', FichaController::class);
        Route::post('/fichas/{ficha}/asignar-instructor', [FichaController::class, 'asignarInstructor'])->name('fichas.asignarInstructor');
        Route::delete('/fichas/{ficha}/quitar-instructor', [FichaController::class, 'quitarInstructor'])->name('fichas.quitarInstructor');
    });

    // ── Instructores (solo admin) ─────────────────────────────
    Route::middleware('role:administrador')->group(function () {
        Route::resource('instructores', InstructorController::class);
    });

    // ── Aprendices ────────────────────────────────────────────
    Route::middleware('role:administrador')->group(function () {
        Route::get('/aprendices/crear',                [AprendizController::class, 'create'])->name('aprendices.create');
        Route::post('/aprendices',                     [AprendizController::class, 'store'])->name('aprendices.store');
        Route::get('/aprendices/{aprendiz}/editar',    [AprendizController::class, 'edit'])->name('aprendices.edit');
        Route::put('/aprendices/{aprendiz}',           [AprendizController::class, 'update'])->name('aprendices.update');
        Route::delete('/aprendices/{aprendiz}',        [AprendizController::class, 'destroy'])->name('aprendices.destroy');
    });
    Route::get('/aprendices',                          [AprendizController::class, 'index'])->name('aprendices.index');
    Route::get('/aprendices/{aprendiz}',               [AprendizController::class, 'show'])->name('aprendices.show');
    Route::get('/aprendices/{aprendiz}/pdf',           [AprendizController::class, 'descargarPdf'])->name('aprendices.pdf');

    // ── Actividades ───────────────────────────────────────────
    Route::middleware('role:administrador,instructor')->group(function () {
        Route::get('/actividades/crear',               [ActividadController::class, 'create'])->name('actividades.create');
        Route::post('/actividades',                    [ActividadController::class, 'store'])->name('actividades.store');
        Route::get('/actividades/{actividad}/editar',  [ActividadController::class, 'edit'])->name('actividades.edit');
        Route::put('/actividades/{actividad}',         [ActividadController::class, 'update'])->name('actividades.update');
        Route::delete('/actividades/{actividad}',      [ActividadController::class, 'destroy'])->name('actividades.destroy');
        Route::patch('/actividades/{actividad}/estado',[ActividadController::class, 'cambiarEstado'])->name('actividades.estado');
        Route::post('/actividades/{actividad}/asignar-ficha',[ActividadController::class, 'asignarFicha'])->name('actividades.asignarFicha');
    });
    Route::get('/actividades',                         [ActividadController::class, 'index'])->name('actividades.index');
    Route::get('/actividades/{actividad}',             [ActividadController::class, 'show'])->name('actividades.show');

    // ── Calificaciones (H8) ───────────────────────────────────
    Route::get('/calificaciones',                      [CalificacionController::class, 'index'])->name('calificaciones.index');
    Route::middleware('role:administrador,instructor')->group(function () {
        Route::get('/calificaciones/actividad',        [CalificacionController::class, 'porActividad'])->name('calificaciones.porActividad');
        Route::post('/calificaciones/actividad',       [CalificacionController::class, 'guardarPorActividad'])->name('calificaciones.guardarPorActividad');
        Route::post('/calificaciones',                 [CalificacionController::class, 'store'])->name('calificaciones.store');
        Route::delete('/calificaciones/{calificacion}',[CalificacionController::class, 'destroy'])->name('calificaciones.destroy');
    });

    // ── Observaciones (H17) ───────────────────────────────────
    Route::middleware('role:administrador,instructor')->group(function () {
        Route::post('/observaciones',                  [ObservacionController::class, 'store'])->name('observaciones.store');
        Route::delete('/observaciones/{observacion}',  [ObservacionController::class, 'destroy'])->name('observaciones.destroy');
    });

    // ── Evaluación Integral (H13) ─────────────────────────────
    Route::middleware('role:administrador,instructor')->group(function () {
        Route::get('/aprendices/{aprendiz}/evaluacion',  [EvaluacionIntegralController::class, 'create'])->name('evaluacion.create');
        Route::post('/aprendices/{aprendiz}/evaluacion', [EvaluacionIntegralController::class, 'store'])->name('evaluacion.store');
    });

    // ── Reportes (H10) ────────────────────────────────────────
    Route::middleware('role:administrador,instructor')->group(function () {
        Route::get('/reportes',           [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/ficha',     [ReporteController::class, 'porFicha'])->name('reportes.porFicha');
        Route::get('/reportes/individual',[ReporteController::class, 'individual'])->name('reportes.individual');
    });
});
