<?php
namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Aprendiz;
use App\Models\Ficha;
use App\Models\InstructorFicha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActividadController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Actividad::with('instructor');

        if ($user->esInstructor()) {
            $query->where('instructor_id', $user->id);
        }

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(fn($q) => $q->where('titulo','like',"%$b%")->orWhere('descripcion','like',"%$b%"));
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('ficha') && !$user->esAprendiz()) {
            $query->where('ficha_asignada', $request->ficha);
        }

        if ($user->esAprendiz()) {
            $aprendiz = $user->aprendiz;
            $actividades = $aprendiz
                ? $aprendiz->actividades()->with('instructor')->latest()->paginate(15)
                : collect();
            return view('actividades.index', compact('actividades'));
        }

        $actividades = $query->latest()->paginate(15)->withQueryString();
        $fichas = $this->fichasDisponibles($user);
        return view('actividades.index', compact('actividades', 'fichas'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->esAprendiz()) abort(403);

        $fichas = $user->esInstructor()
            ? $user->fichasAsignadas()->pluck('ficha')->toArray()
            : Ficha::where('estado','activo')->orderBy('numero')->pluck('numero')->toArray();

        return view('actividades.create', compact('fichas'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->esAprendiz()) abort(403);

        $request->validate([
            'titulo'         => 'required|string|max:255',
            'descripcion'    => 'nullable|string|max:2000',
            'fecha_limite'   => 'required|date',
            'estado'         => 'required|in:pendiente,en_proceso,completada,retrasada',
            'porcentaje_peso'=> 'required|integer|min:0|max:100',
            'ficha_asignada' => 'required|string|max:50',
        ]);

        // H7 - instructor automático según sesión
        $instructorId = $user->esInstructor() ? $user->id : $request->instructor_id;

        $actividad = Actividad::create([
            'titulo'          => $request->titulo,
            'descripcion'     => $request->descripcion,
            'instructor_id'   => $instructorId,
            'fecha_limite'    => $request->fecha_limite,
            'estado'          => $request->estado,
            'porcentaje_peso' => $request->porcentaje_peso,
            'ficha_asignada'  => $request->ficha_asignada,
        ]);

        // H6 - asignar automáticamente a todos los aprendices de la ficha
        $this->asignarAprendicesDeFicha($actividad, $request->ficha_asignada);

        return redirect()->route('actividades.index')
            ->with('success', "Actividad creada y asignada a todos los aprendices de la ficha {$request->ficha_asignada}.");
    }

    public function show(Actividad $actividad)
    {
        $user = Auth::user();
        if ($user->esInstructor() && $actividad->instructor_id !== $user->id) abort(403);

        $actividad->load(['instructor', 'aprendices.user', 'calificaciones']);
        return view('actividades.show', compact('actividad'));
    }

    public function edit(Actividad $actividad)
    {
        $user = Auth::user();
        if ($user->esAprendiz()) abort(403);
        if ($user->esInstructor() && $actividad->instructor_id !== $user->id) abort(403);

        $fichas = Ficha::where('estado','activo')->orderBy('numero')->pluck('numero')->toArray();
        return view('actividades.edit', compact('actividad', 'fichas'));
    }

    public function update(Request $request, Actividad $actividad)
    {
        $user = Auth::user();
        if ($user->esAprendiz()) abort(403);
        if ($user->esInstructor() && $actividad->instructor_id !== $user->id) abort(403);

        $request->validate([
            'titulo'         => 'required|string|max:255',
            'descripcion'    => 'nullable|string|max:2000',
            'fecha_limite'   => 'required|date',
            'estado'         => 'required|in:pendiente,en_proceso,completada,retrasada',
            'porcentaje_peso'=> 'required|integer|min:0|max:100',
            'ficha_asignada' => 'required|string|max:50',
        ]);

        $fichaAnterior = $actividad->ficha_asignada;
        $actividad->update($request->only([
            'titulo','descripcion','fecha_limite','estado','porcentaje_peso','ficha_asignada',
        ]));

        // Si cambió la ficha, reasignar aprendices
        if ($fichaAnterior !== $request->ficha_asignada) {
            $actividad->aprendices()->detach();
            $this->asignarAprendicesDeFicha($actividad, $request->ficha_asignada);
        }

        return redirect()->route('actividades.index')
            ->with('success', 'Actividad actualizada correctamente.');
    }

    public function destroy(Actividad $actividad)
    {
        $user = Auth::user();
        if ($user->esAprendiz()) abort(403);
        if ($user->esInstructor() && $actividad->instructor_id !== $user->id) abort(403);

        $actividad->delete();
        return redirect()->route('actividades.index')
            ->with('success', 'Actividad eliminada correctamente.');
    }

    public function cambiarEstado(Request $request, Actividad $actividad)
    {
        $request->validate(['estado' => 'required|in:pendiente,en_proceso,completada,retrasada']);
        $actividad->update(['estado' => $request->estado]);
        return back()->with('success', 'Estado actualizado.');
    }

    public function asignarFicha(Request $request, Actividad $actividad)
    {
        $request->validate(['ficha' => 'required|string|max:50']);
        $actividad->update(['ficha_asignada' => $request->ficha]);
        $actividad->aprendices()->detach();
        $this->asignarAprendicesDeFicha($actividad, $request->ficha);
        return back()->with('success', 'Ficha reasignada y aprendices actualizados.');
    }

    // H6 — asignación automática
    private function asignarAprendicesDeFicha(Actividad $actividad, string $ficha): void
    {
        $aprendices = Aprendiz::where('ficha', $ficha)
                              ->where('estado', '!=', 'retirado')
                              ->pluck('id');

        $sync = $aprendices->mapWithKeys(fn($id) => [$id => ['estado' => 'pendiente']])->toArray();
        $actividad->aprendices()->syncWithoutDetaching($sync);
    }

    private function fichasDisponibles($user): array
    {
        if ($user->esInstructor()) {
            return $user->fichasAsignadas()->pluck('ficha')->toArray();
        }
        return Ficha::orderBy('numero')->pluck('numero')->toArray();
    }
}
