<?php

namespace App\Http\Controllers;

use App\Models\Aprendiz;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeguimientoController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Aprendiz::with(['user', 'seguimientos'])
                         ->where('estado', 'activo');

        // Instructor: solo aprendices de sus fichas
        if ($user->esInstructor()) {
            $fichas = $user->fichas ?? [];
            $query->whereIn('ficha', $fichas);
        }

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$b%"));
        }
        if ($request->filled('ficha')) {
            $query->where('ficha', $request->ficha);
        }

        $aprendices = $query->latest()->paginate(15)->withQueryString();
        $fichas     = Aprendiz::distinct()->orderBy('ficha')->pluck('ficha')->toArray();

        return view('seguimientos.index', compact('aprendices', 'fichas'));
    }

    public function show(Aprendiz $aprendiz)
    {
        $this->verificarAccesoAprendiz($aprendiz);

        $aprendiz->load(['user', 'actividades']);
        $historial = $aprendiz->seguimientos()->with('instructor')->latest('fecha_seguimiento')->get();

        // Calcular progreso basado en actividades completadas
        $total    = $aprendiz->actividades->count();
        $completas = $aprendiz->actividades->where('pivot.estado', 'completada')->count();
        $progreso = $total > 0 ? round(($completas / $total) * 100, 1) : 0;

        return view('seguimientos.show', compact('aprendiz', 'historial', 'progreso'));
    }

    public function store(Request $request, Aprendiz $aprendiz)
    {
        $this->verificarAccesoAprendiz($aprendiz);

        $request->validate([
            'fecha_seguimiento' => 'required|date|before_or_equal:today',
            'comentario'        => 'required|string|min:10|max:2000',
        ], [
            'fecha_seguimiento.required'       => 'La fecha es obligatoria.',
            'fecha_seguimiento.before_or_equal' => 'La fecha no puede ser futura.',
            'comentario.required'              => 'La observación es obligatoria.',
            'comentario.min'                   => 'La observación debe tener al menos 10 caracteres.',
        ]);

        // Calcular progreso actual
        $total    = $aprendiz->actividades()->count();
        $completas = $aprendiz->actividades()->wherePivot('estado', 'completada')->count();
        $progreso  = $total > 0 ? round(($completas / $total) * 100, 1) : 0;

        Seguimiento::create([
            'aprendiz_id'       => $aprendiz->id,
            'instructor_id'     => Auth::id(),
            'comentario'        => $request->comentario,
            'fecha_seguimiento' => $request->fecha_seguimiento,
            'porcentaje'        => $progreso,
        ]);

        return redirect()->route('seguimientos.show', $aprendiz)
            ->with('success', 'Seguimiento registrado correctamente.');
    }

    public function destroy(Seguimiento $seguimiento)
    {
        $user = Auth::user();
        // Solo admin o el instructor que lo creó
        if (!$user->esAdministrador() && $seguimiento->instructor_id !== $user->id) {
            abort(403, 'No tienes permiso para eliminar este seguimiento.');
        }

        $aprendizId = $seguimiento->aprendiz_id;
        $seguimiento->delete();

        return redirect()->route('seguimientos.show', $aprendizId)
            ->with('success', 'Seguimiento eliminado correctamente.');
    }

    private function verificarAccesoAprendiz(Aprendiz $aprendiz): void
    {
        $user = Auth::user();
        if ($user->esInstructor()) {
            $fichas = $user->fichas ?? [];
            if (!in_array($aprendiz->ficha, $fichas)) {
                abort(403, 'No tienes acceso a este aprendiz.');
            }
        }
    }
}
