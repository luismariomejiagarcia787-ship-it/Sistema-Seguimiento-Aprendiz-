<?php
namespace App\Http\Controllers;

use App\Models\Aprendiz;
use App\Models\Actividad;
use App\Models\Calificacion;
use App\Models\User;
use App\Models\Ficha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $fichas = $this->fichasDisponibles($user);
        return view('reportes.index', compact('fichas'));
    }

    // Reporte por ficha (H10)
    public function porFicha(Request $request)
    {
        $user   = Auth::user();
        $fichas = $this->fichasDisponibles($user);

        if (!$request->filled('ficha')) {
            return view('reportes.por_ficha', ['fichas' => $fichas, 'fichaSeleccionada' => null, 'datos' => null]);
        }

        $fichaNumero = $request->ficha;

        if ($user->esInstructor() && !in_array($fichaNumero, $fichas)) {
            abort(403, 'No tienes acceso a esa ficha.');
        }

        $aprendices = Aprendiz::with([
            'user',
            'calificaciones.actividad',
            'evaluacionesIntegrales',
        ])->where('ficha', $fichaNumero)->get();

        $datos = $aprendices->map(function ($ap) {
            return [
                'aprendiz'       => $ap,
                'definitiva'     => $ap->calcularDefinitiva(),
                'indice_integral'=> $ap->calcularIndiceIntegral(),
                'total_actividades' => $ap->actividades()->count(),
                'actividades_calificadas' => $ap->calificaciones()->count(),
            ];
        });

        $stats = [
            'total'              => $aprendices->count(),
            'activos'            => $aprendices->where('estado', 'activo')->count(),
            'promedio_definitiva'=> round($datos->avg('definitiva'), 2),
            'promedio_integral'  => round($datos->avg('indice_integral'), 2),
        ];

        $fichaModelo = Ficha::where('numero', $fichaNumero)->first();

        return view('reportes.por_ficha', compact('fichas', 'fichaNumero', 'fichaModelo', 'datos', 'stats'));
    }

    // Reporte individual del aprendiz
    public function individual(Request $request)
    {
        $user = Auth::user();
        $request->validate(['aprendiz_id' => 'required|exists:aprendices,id']);

        $aprendiz = Aprendiz::with([
            'user',
            'actividades.instructor',
            'calificaciones.actividad',
            'calificaciones.instructor',
            'observaciones.instructor',
            'evaluacionesIntegrales.instructor',
        ])->findOrFail($request->aprendiz_id);

        if ($user->esInstructor()) {
            $fichas = $user->fichasAsignadas()->pluck('ficha')->toArray();
            if (!in_array($aprendiz->ficha, $fichas)) abort(403);
        }

        $definitiva         = $aprendiz->calcularDefinitiva();
        $indiceIntegral     = $aprendiz->calcularIndiceIntegral();
        $promediosCriterios = $aprendiz->promediosCriterios();
        $criteriosLabels    = \App\Models\EvaluacionIntegral::criterios();

        return view('reportes.individual', compact(
            'aprendiz', 'definitiva', 'indiceIntegral',
            'promediosCriterios', 'criteriosLabels'
        ));
    }

    private function fichasDisponibles($user): array
    {
        if ($user->esInstructor()) {
            return $user->fichasAsignadas()->pluck('ficha')->toArray();
        }
        return Ficha::orderBy('numero')->pluck('numero')->toArray();
    }
}
