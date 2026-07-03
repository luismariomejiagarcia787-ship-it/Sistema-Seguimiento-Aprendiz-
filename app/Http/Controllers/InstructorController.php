<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\InstructorFicha;
use App\Models\Aprendiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class InstructorController extends Controller
{
    // La protección de rutas se hace en web.php con middleware('role:administrador')
    // No usar __construct con abort() ya que se ejecuta antes de auth middleware

    public function index(Request $request)
    {
        $query = User::where('rol', 'instructor')->with('fichasAsignadas');

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(fn($q) => $q->where('name', 'like', "%$buscar%")->orWhere('email', 'like', "%$buscar%"));
        }

        $instructores = $query->latest()->paginate(15)->withQueryString();
        return view('instructores.index', compact('instructores'));
    }

    public function create()
    {
        $fichasExistentes = Aprendiz::distinct()->orderBy('ficha')->pluck('ficha')->toArray();
        return view('instructores.create', compact('fichasExistentes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'telefono' => 'nullable|string|max:20',
            'foto'     => 'nullable|image|max:2048',
            'fichas'   => 'nullable|array',
            'fichas.*' => 'string|max:50',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        $instructor = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'rol'      => 'instructor',
            'telefono' => $request->telefono,
            'foto'     => $fotoPath,
        ]);

        if ($request->filled('fichas')) {
            foreach (array_unique($request->fichas) as $ficha) {
                if (!empty(trim($ficha))) {
                    InstructorFicha::create(['user_id' => $instructor->id, 'ficha' => trim($ficha)]);
                }
            }
        }

        return redirect()->route('instructores.index')->with('success', 'Instructor creado correctamente.');
    }

    public function show(User $instructor)
    {
        $this->verificarEsInstructor($instructor);
        $instructor->load('fichasAsignadas');
        $aprendicesFichas = [];
        foreach ($instructor->fichas as $ficha) {
            $aprendicesFichas[$ficha] = Aprendiz::with('user')->where('ficha', $ficha)->get();
        }
        return view('instructores.show', compact('instructor', 'aprendicesFichas'));
    }

    public function edit(User $instructor)
    {
        $this->verificarEsInstructor($instructor);
        $instructor->load('fichasAsignadas');
        $fichasExistentes = Aprendiz::distinct()->orderBy('ficha')->pluck('ficha')->toArray();
        return view('instructores.edit', compact('instructor', 'fichasExistentes'));
    }

    public function update(Request $request, User $instructor)
    {
        $this->verificarEsInstructor($instructor);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $instructor->id,
            'telefono' => 'nullable|string|max:20',
            'foto'     => 'nullable|image|max:2048',
            'fichas'   => 'nullable|array',
            'fichas.*' => 'string|max:50',
        ]);

        $fotoPath = $instructor->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath) Storage::disk('public')->delete($fotoPath);
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        $instructor->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'foto'     => $fotoPath,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Password::defaults()]]);
            $instructor->update(['password' => Hash::make($request->password)]);
        }

        InstructorFicha::where('user_id', $instructor->id)->delete();
        if ($request->filled('fichas')) {
            foreach (array_unique($request->fichas) as $ficha) {
                if (!empty(trim($ficha))) {
                    InstructorFicha::create(['user_id' => $instructor->id, 'ficha' => trim($ficha)]);
                }
            }
        }

        return redirect()->route('instructores.index')->with('success', 'Instructor actualizado correctamente.');
    }

    public function destroy(User $instructor)
    {
        $this->verificarEsInstructor($instructor);
        if ($instructor->foto) Storage::disk('public')->delete($instructor->foto);
        $instructor->delete();
        return redirect()->route('instructores.index')->with('success', 'Instructor eliminado correctamente.');
    }

    private function verificarEsInstructor(User $user): void
    {
        if ($user->rol !== 'instructor') {
            abort(404, 'Instructor no encontrado.');
        }
    }
}
