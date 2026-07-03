<?php
namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Actividad;
use App\Models\Aprendiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalificacionController extends Controller
{
    // Listar calificaciones por ficha o aprendiz
    public function index(Request $request)
    {
        $user  = Auth::user();

        if ($user->esAprendiz()) {
            $aprendiz = $user->aprendiz;
            if (!$aprendiz) return view('calificaciones.index', ['calificaciones' => collect(), 'definitiva' => 0]);

            $calificaciones = $aprendiz->calificaciones()->with('actividad', 'instructor')->get();
            $definitiva     = $aprendiz->calcularDefinitiva();
            return view('calificaciones.index', compact('calificaciones', 'definitiva'));
        }

        // Instructor o admin
        $query = Calificacion::with(['aprendiz.user', 'actividad', 'instructor']);

        if ($user->esInstructor()) {
            $fichas = $user->fichas ?? [];
            $query->whereHas('aprendiz', fn($q) => $q->whereIn('ficha', $fichas));
        }

        if ($request->filled('ficha')) {
            $query->whereHas('aprendiz', fn($q) => $q->where('ficha', $request->ficha));
        }
        if ($request->filled('aprendiz_id')) {
            $query->where('aprendiz_id', $request->aprendiz_id);
        }
        if ($request->filled('actividad_id')) {
            $query->where('actividad_id', $request->actividad_id);
        }

        $calificaciones = $query->latest()->paginate(20)->withQueryString();
        $fichas         = $this->fichasDisponibles($user);
        $definitiva     = null;

        return view('calificaciones.index', compact('calificaciones', 'fichas', 'definitiva'));
    }

    // Registrar / actualizar nota (H8)
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->esAprendiz()) abort(403);

        $request->validate([
            'aprendiz_id'  => 'required|exists:aprendices,id',
            'actividad_id' => 'required|exists:actividades,id',
            'nota'         => 'required|numeric|min:0|max:10',
            'observacion'  => 'nullable|string|max:500',
        ], [
            'nota.min' => 'La nota mínima es 0.',
            'nota.max' => 'La nota máxima es 10.',
        ]);

        // Verificar acceso instructor a ese aprendiz
        if ($user->esInstructor()) {
            $aprendiz = Aprendiz::findOrFail($request->aprendiz_id);
            if (!in_array($aprendiz->ficha, $user->fichas ?? [])) {
                abort(403, 'No tienes acceso a ese aprendiz.');
            }
        }

        Calificacion::updateOrCreate(
            ['aprendiz_id' => $request->aprendiz_id, 'actividad_id' => $request->actividad_id],
            [
                'instructor_id' => $user->id,
                'nota'          => $request->nota,
                'observacion'   => $request->observacion,
            ]
        );

        return back()->with('success', 'Calificación registrada correctamente.');
    }

    // Formulario para registrar notas de una actividad completa (por ficha)
    public function porActividad(Request $request)
    {
        $user = Auth::user();
        if ($user->esAprendiz()) abort(403);

        $actividadesQuery = Actividad::with('instructor');
        if ($user->esInstructor()) {
            $actividadesQuery->where('instructor_id', $user->id);
        }
        $actividades = $actividadesQuery->latest()->get();

        $actividad      = null;
        $aprendices     = collect();
        $calificaciones = [];

        if ($request->filled('actividad_id')) {
            $actividad = Actividad::with('aprendices.user', 'calificaciones')->findOrFail($request->actividad_id);

            // Verificar acceso
            if ($user->esInstructor() && $actividad->instructor_id !== $user->id) {
                abort(403);
            }

            $aprendices = $actividad->aprendices()->with('user')->get();
            foreach ($actividad->calificaciones as $cal) {
                $calificaciones[$cal->aprendiz_id] = $cal;
            }
        }

        return view('calificaciones.por_actividad', compact('actividades', 'actividad', 'aprendices', 'calificaciones'));
    }

    // Guardar múltiples calificaciones de una actividad
    public function guardarPorActividad(Request $request)
    {
        $user = Auth::user();
        if ($user->esAprendiz()) abort(403);

        $request->validate([
            'actividad_id'     => 'required|exists:actividades,id',
            'notas'            => 'required|array',
            'notas.*'          => 'nullable|numeric|min:0|max:10',
            'observaciones'    => 'nullable|array',
            'observaciones.*'  => 'nullable|string|max:500',
        ]);

        $actividad = Actividad::findOrFail($request->actividad_id);
        if ($user->esInstructor() && $actividad->instructor_id !== $user->id) abort(403);

        foreach ($request->notas as $aprendizId => $nota) {
            if ($nota === null || $nota === '') continue;

            // Verificar que aprendiz pertenece a la actividad
            if (!$actividad->aprendices()->where('aprendices.id', $aprendizId)->exists()) continue;

            Calificacion::updateOrCreate(
                ['aprendiz_id' => $aprendizId, 'actividad_id' => $request->actividad_id],
                [
                    'instructor_id' => $user->id,
                    'nota'          => $nota,
                    'observacion'   => $request->observaciones[$aprendizId] ?? null,
                ]
            );
        }

        return back()->with('success', 'Calificaciones guardadas correctamente.');
    }

    public function destroy(Calificacion $calificacion)
    {
        $user = Auth::user();
        if ($user->esAprendiz()) abort(403);
        if ($user->esInstructor() && $calificacion->instructor_id !== $user->id) abort(403);

        $calificacion->delete();
        return back()->with('success', 'Calificación eliminada.');
    }

    private function fichasDisponibles($user): array
    {
        if ($user->esInstructor()) {
            return $user->fichasAsignadas()->pluck('ficha')->toArray();
        }
        return Aprendiz::distinct()->orderBy('ficha')->pluck('ficha')->toArray();
    }
}
