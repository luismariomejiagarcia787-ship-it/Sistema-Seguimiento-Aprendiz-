<?php
namespace App\Http\Controllers;

use App\Models\Aprendiz;
use App\Models\Actividad;
use App\Models\Calificacion;
use App\Models\User;
use App\Models\Ficha;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->esAdministrador()) return $this->admin();
        if ($user->esInstructor())    return $this->instructor($user);
        return $this->aprendiz($user);
    }

    private function admin()
    {
        $stats = [
            'total_aprendices'   => Aprendiz::count(),
            'aprendices_activos' => Aprendiz::where('estado', 'activo')->count(),
            'total_instructores' => User::where('rol', 'instructor')->count(),
            'total_fichas'       => Ficha::count(),
            'total_actividades'  => Actividad::count(),
            'total_calificaciones' => Calificacion::count(),
        ];

        $actividades_por_estado = Actividad::selectRaw('estado, count(*) as total')
            ->groupBy('estado')->pluck('total', 'estado')->toArray();

        $aprendices_recientes = Aprendiz::with('user')->latest()->limit(5)->get();

        $fichas_resumen = Ficha::withCount('aprendices')->latest()->limit(5)->get();

        return view('dashboard.admin', compact(
            'stats', 'actividades_por_estado', 'aprendices_recientes', 'fichas_resumen'
        ));
    }

    private function instructor(User $user)
    {
        $fichas    = $user->fichas;
        $aprendices = Aprendiz::with('user')->whereIn('ficha', $fichas)->where('estado', 'activo')->get();

        $stats = [
            'total_aprendices'    => $aprendices->count(),
            'fichas_asignadas'    => count($fichas),
            'actividades_creadas' => Actividad::where('instructor_id', $user->id)->count(),
            'calificaciones_pendientes' => $aprendices->sum(fn($a) =>
                $a->actividades()->count() - $a->calificaciones()->count()
            ),
        ];

        $actividades_recientes = Actividad::where('instructor_id', $user->id)
            ->latest()->limit(5)->get();

        return view('dashboard.instructor', compact(
            'stats', 'aprendices', 'actividades_recientes', 'fichas'
        ));
    }

    private function aprendiz(User $user)
    {
        $aprendiz = $user->aprendiz;
        if (!$aprendiz) {
            return view('dashboard.aprendiz', [
                'aprendiz'    => null,
                'definitiva'  => 0,
                'calificaciones' => collect(),
                'actividades_pendientes' => collect(),
                'observaciones' => collect(),
            ]);
        }

        $aprendiz->load(['actividades', 'calificaciones.actividad', 'observaciones.instructor']);
        $definitiva              = $aprendiz->calcularDefinitiva();
        $calificaciones          = $aprendiz->calificaciones()->with('actividad')->latest()->get();
        $actividades_pendientes  = $aprendiz->actividades()->wherePivotIn('estado', ['pendiente', 'en_proceso'])->get();
        $observaciones           = $aprendiz->observaciones()->with('instructor')->latest()->limit(5)->get();

        return view('dashboard.aprendiz', compact(
            'aprendiz', 'definitiva', 'calificaciones',
            'actividades_pendientes', 'observaciones'
        ));
    }
}
