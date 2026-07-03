<?php
namespace App\Http\Controllers;

use App\Models\Ficha;
use App\Models\Aprendiz;
use App\Models\InstructorFicha;
use App\Models\User;
use Illuminate\Http\Request;

class FichaController extends Controller
{
    public function index(Request $request)
    {
        $query = Ficha::query();

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(fn($q) => $q->where('numero', 'like', "%$b%")
                ->orWhere('programa_formacion', 'like', "%$b%"));
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $fichas = $query->latest()->paginate(15)->withQueryString();
        return view('fichas.index', compact('fichas'));
    }

    public function create()
    {
        return view('fichas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero'             => 'required|string|max:50|unique:fichas,numero',
            'programa_formacion' => 'required|string|max:255',
            'fecha_inicio'       => 'required|date',
            'fecha_fin'          => 'nullable|date|after:fecha_inicio',
            'estado'             => 'required|in:activo,inactivo,terminado',
            'descripcion'        => 'nullable|string|max:1000',
        ], ['numero.unique' => 'Ya existe una ficha con ese número.']);

        Ficha::create($request->only(['numero','programa_formacion','fecha_inicio','fecha_fin','estado','descripcion']));

        return redirect()->route('fichas.index')->with('success', 'Ficha creada correctamente.');
    }

    public function show(Ficha $ficha)
    {
        $aprendices   = Aprendiz::with('user')->where('ficha', $ficha->numero)->get();
        $instructores = InstructorFicha::with('instructor')->where('ficha', $ficha->numero)->get();
        return view('fichas.show', compact('ficha', 'aprendices', 'instructores'));
    }

    public function edit(Ficha $ficha)
    {
        return view('fichas.edit', compact('ficha'));
    }

    public function update(Request $request, Ficha $ficha)
    {
        $request->validate([
            'numero'             => 'required|string|max:50|unique:fichas,numero,' . $ficha->id,
            'programa_formacion' => 'required|string|max:255',
            'fecha_inicio'       => 'required|date',
            'fecha_fin'          => 'nullable|date|after:fecha_inicio',
            'estado'             => 'required|in:activo,inactivo,terminado',
            'descripcion'        => 'nullable|string|max:1000',
        ]);

        $ficha->update($request->only(['numero','programa_formacion','fecha_inicio','fecha_fin','estado','descripcion']));

        return redirect()->route('fichas.index')->with('success', 'Ficha actualizada correctamente.');
    }

    public function destroy(Ficha $ficha)
    {
        $hasAprendices = Aprendiz::where('ficha', $ficha->numero)->exists();
        if ($hasAprendices) {
            return back()->with('error', 'No se puede eliminar la ficha porque tiene aprendices registrados.');
        }
        $ficha->delete();
        return redirect()->route('fichas.index')->with('success', 'Ficha eliminada correctamente.');
    }

    public function asignarInstructor(Request $request, Ficha $ficha)
    {
        $request->validate([
            'instructor_id' => 'required|exists:users,id',
        ]);

        $instructor = User::findOrFail($request->instructor_id);
        if ($instructor->rol !== 'instructor') {
            return back()->with('error', 'El usuario no es instructor.');
        }

        InstructorFicha::firstOrCreate([
            'user_id' => $request->instructor_id,
            'ficha'   => $ficha->numero,
        ]);

        return back()->with('success', 'Instructor asignado correctamente.');
    }

    public function quitarInstructor(Request $request, Ficha $ficha)
    {
        $request->validate(['instructor_id' => 'required|exists:users,id']);
        InstructorFicha::where('user_id', $request->instructor_id)
                        ->where('ficha', $ficha->numero)->delete();
        return back()->with('success', 'Instructor removido de la ficha.');
    }
}
