<?php
namespace App\Http\Controllers;

use App\Models\Aprendiz;
use App\Models\Ficha;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AprendizController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Aprendiz::with('user');

        if ($user->esInstructor()) {
            $fichas = $user->fichas ?? [];
            $query->whereIn('ficha', $fichas);
        }

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function($q) use ($b) {
                $q->whereHas('user', fn($q2) => $q2->where('name', 'like', "%$b%")
                    ->orWhere('email', 'like', "%$b%"))
                  ->orWhere('documento', 'like', "%$b%");
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('ficha')) {
            $query->where('ficha', $request->ficha);
        }

        $aprendices = $query->latest()->paginate(15)->withQueryString();
        $fichas     = Ficha::orderBy('numero')->pluck('numero')->toArray();

        return view('aprendices.index', compact('aprendices', 'fichas'));
    }

    public function create()
    {
        $fichas = Ficha::where('estado', 'activo')->orderBy('numero')->get();
        return view('aprendices.create', compact('fichas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email',
            'password'           => ['required', 'confirmed', Password::defaults()],
            'documento'          => 'required|string|max:20|unique:aprendices,documento',
            'programa_formacion' => 'required|string|max:255',
            'ficha'              => 'required|string|max:50',
            'fecha_inicio'       => 'required|date',
            'estado'             => 'required|in:activo,inactivo,egresado,retirado',
            'telefono'           => 'nullable|string|max:20',
            'foto'               => 'nullable|image|max:2048',
            'observaciones'      => 'nullable|string|max:1000',
        ], [
            'email.unique'       => 'Este correo ya está registrado.',
            'documento.unique'   => 'Este documento ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'rol'      => 'aprendiz',
            'telefono' => $request->telefono,
            'foto'     => $fotoPath,
        ]);

        Aprendiz::create([
            'user_id'            => $user->id,
            'documento'          => $request->documento,
            'programa_formacion' => $request->programa_formacion,
            'ficha'              => $request->ficha,
            'fecha_inicio'       => $request->fecha_inicio,
            'estado'             => $request->estado,
            'telefono'           => $request->telefono,
            'observaciones'      => $request->observaciones,
        ]);

        return redirect()->route('aprendices.index')
            ->with('success', 'Aprendiz registrado correctamente.');
    }

    // Perfil completo tipo hoja de vida (H11, H12)
    public function show(Aprendiz $aprendiz)
    {
        $user = Auth::user();

        // Si es aprendiz, solo puede ver su propio perfil
        if ($user->esAprendiz()) {
            if (!$user->aprendiz || $user->aprendiz->id !== $aprendiz->id) {
                abort(403);
            }
        }

        // Instructor: solo sus fichas
        if ($user->esInstructor()) {
            if (!in_array($aprendiz->ficha, $user->fichas ?? [])) {
                abort(403);
            }
        }

        $aprendiz->load([
            'user',
            'actividades.instructor',
            'calificaciones.actividad',
            'calificaciones.instructor',
            'observaciones.instructor',
            'evaluacionesIntegrales.instructor',
        ]);

        $definitiva    = $aprendiz->calcularDefinitiva();
        $indiceIntegral = $aprendiz->calcularIndiceIntegral();
        $promediosCriterios = $aprendiz->promediosCriterios();
        $criteriosLabels    = \App\Models\EvaluacionIntegral::criterios();

        return view('aprendices.show', compact(
            'aprendiz', 'definitiva', 'indiceIntegral',
            'promediosCriterios', 'criteriosLabels'
        ));
    }

    public function edit(Aprendiz $aprendiz)
    {
        $aprendiz->load('user');
        $fichas = Ficha::orderBy('numero')->get();
        return view('aprendices.edit', compact('aprendiz', 'fichas'));
    }

    public function update(Request $request, Aprendiz $aprendiz)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email,' . $aprendiz->user_id,
            'documento'          => 'required|string|max:20|unique:aprendices,documento,' . $aprendiz->id,
            'programa_formacion' => 'required|string|max:255',
            'ficha'              => 'required|string|max:50',
            'fecha_inicio'       => 'required|date',
            'estado'             => 'required|in:activo,inactivo,egresado,retirado',
            'telefono'           => 'nullable|string|max:20',
            'foto'               => 'nullable|image|max:2048',
            'observaciones'      => 'nullable|string|max:1000',
        ], [
            'email.unique'     => 'Este correo ya está registrado por otro usuario.',
            'documento.unique' => 'Este documento ya está registrado por otro aprendiz.',
        ]);

        $fotoPath = $aprendiz->user->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath) Storage::disk('public')->delete($fotoPath);
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        $aprendiz->user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'foto'     => $fotoPath,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Password::defaults()]]);
            $aprendiz->user->update(['password' => Hash::make($request->password)]);
        }

        $aprendiz->update([
            'documento'          => $request->documento,
            'programa_formacion' => $request->programa_formacion,
            'ficha'              => $request->ficha,
            'fecha_inicio'       => $request->fecha_inicio,
            'estado'             => $request->estado,
            'telefono'           => $request->telefono,
            'observaciones'      => $request->observaciones,
        ]);

        return redirect()->route('aprendices.index')
            ->with('success', 'Aprendiz actualizado correctamente.');
    }

    public function destroy(Aprendiz $aprendiz)
    {
        if ($aprendiz->user->foto) {
            Storage::disk('public')->delete($aprendiz->user->foto);
        }
        $aprendiz->user->delete();
        return redirect()->route('aprendices.index')
            ->with('success', 'Aprendiz eliminado correctamente.');
    }

    // Descargar hoja de vida PDF (H18)
    public function descargarPdf(Aprendiz $aprendiz)
    {
        $user = Auth::user();

        if ($user->esAprendiz() && $user->aprendiz->id !== $aprendiz->id) abort(403);
        if ($user->esInstructor() && !in_array($aprendiz->ficha, $user->fichas ?? [])) abort(403);

        $aprendiz->load([
            'user',
            'actividades.instructor',
            'calificaciones.actividad',
            'calificaciones.instructor',
            'observaciones.instructor',
            'evaluacionesIntegrales.instructor',
        ]);

        $definitiva         = $aprendiz->calcularDefinitiva();
        $indiceIntegral     = $aprendiz->calcularIndiceIntegral();
        $promediosCriterios = $aprendiz->promediosCriterios();
        $criteriosLabels    = \App\Models\EvaluacionIntegral::criterios();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('aprendices.pdf', compact(
            'aprendiz', 'definitiva', 'indiceIntegral',
            'promediosCriterios', 'criteriosLabels'
        ))->setPaper('a4', 'portrait');

        $nombre = 'HojaVida_' . str_replace(' ', '_', $aprendiz->user->name) . '_' . now()->format('Ymd') . '.pdf';
        return $pdf->download($nombre);
    }
}
