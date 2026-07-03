<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\Actividad;
use App\Models\Aprendiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EntregaController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Entrega::with(['aprendiz.user', 'actividad', 'revisor']);

        // Aprendiz: solo sus propias entregas
        if ($user->esAprendiz()) {
            $aprendiz = $user->aprendiz;
            if (!$aprendiz) {
                return view('evidencias.index', [
                    'entregas' => collect()->paginate(15),
                    'fichas'   => [],
                ]);
            }
            $query->where('aprendiz_id', $aprendiz->id);
        }

        // Instructor: solo entregas de sus aprendices (fichas asignadas)
        if ($user->esInstructor()) {
            $fichas     = $user->fichas ?? [];
            $aprendices = Aprendiz::whereIn('ficha', $fichas)->pluck('id');
            $query->whereIn('aprendiz_id', $aprendices);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('ficha')) {
            $query->whereHas('aprendiz', fn($q) => $q->where('ficha', $request->ficha));
        }

        $entregas = $query->latest()->paginate(15)->withQueryString();
        $fichas   = !$user->esAprendiz()
            ? Aprendiz::distinct()->orderBy('ficha')->pluck('ficha')->toArray()
            : [];

        return view('evidencias.index', compact('entregas', 'fichas'));
    }

    public function create()
    {
        $user     = Auth::user();
        $aprendiz = $user->aprendiz;

        if (!$aprendiz) {
            return redirect()->route('evidencias.index')
                ->with('error', 'Tu perfil de aprendiz no está configurado. Contacta al administrador.');
        }

        // Solo actividades asignadas al aprendiz con estado pendiente o en_proceso
        $actividades = $aprendiz->actividades()
            ->wherePivotIn('estado', ['pendiente', 'en_proceso'])
            ->get();

        return view('evidencias.create', compact('actividades'));
    }

    public function store(Request $request)
    {
        $user     = Auth::user();
        $aprendiz = $user->aprendiz;

        if (!$aprendiz) {
            return redirect()->route('evidencias.index')
                ->with('error', 'Perfil de aprendiz no encontrado.');
        }

        $request->validate([
            'actividad_id' => 'required|exists:actividades,id',
            'archivo'      => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,zip',
        ], [
            'actividad_id.required' => 'Debes seleccionar una actividad.',
            'archivo.required'      => 'Debes adjuntar un archivo.',
            'archivo.max'           => 'El archivo no puede superar los 10 MB.',
            'archivo.mimes'         => 'Formato no permitido. Usa PDF, Word, imagen o ZIP.',
        ]);

        // Verificar que la actividad esté asignada a este aprendiz
        $asignada = $aprendiz->actividades()
            ->where('actividades.id', $request->actividad_id)
            ->exists();

        if (!$asignada) {
            return back()->withErrors(['actividad_id' => 'No tienes acceso a esa actividad.']);
        }

        // Verificar que no haya entregado ya esta actividad
        $yaEntrego = Entrega::where('aprendiz_id', $aprendiz->id)
            ->where('actividad_id', $request->actividad_id)
            ->whereIn('estado', ['en_revision', 'aprobada'])
            ->exists();

        if ($yaEntrego) {
            return back()->withErrors(['actividad_id' => 'Ya tienes una entrega en revisión o aprobada para esta actividad.']);
        }

        // Guardar archivo con nombre único
        $archivo       = $request->file('archivo');
        $nombreArchivo = $aprendiz->id . '_' . time() . '_' . $archivo->getClientOriginalName();
        $ruta          = $archivo->storeAs('evidencias', $nombreArchivo, 'public');

        Entrega::create([
            'aprendiz_id'    => $aprendiz->id,
            'actividad_id'   => $request->actividad_id,
            'archivo'        => $ruta,
            'nombre_archivo' => $archivo->getClientOriginalName(),
            'tipo_archivo'   => $archivo->getMimeType(),
            'estado'         => 'en_revision',
        ]);

        // Actualizar estado pivot del aprendiz en la actividad
        $aprendiz->actividades()->updateExistingPivot($request->actividad_id, ['estado' => 'en_proceso']);

        return redirect()->route('evidencias.index')
            ->with('success', 'Evidencia enviada correctamente. Quedará en revisión.');
    }

    public function show(Entrega $entrega)
    {
        $this->verificarAccesoEntrega($entrega);
        $entrega->load(['aprendiz.user', 'actividad.instructor', 'revisor']);
        return view('evidencias.show', compact('entrega'));
    }

    public function download(Entrega $entrega)
    {
        $this->verificarAccesoEntrega($entrega);

        if (!Storage::disk('public')->exists($entrega->archivo)) {
            return back()->with('error', 'El archivo no existe en el servidor.');
        }

        return Storage::disk('public')->download($entrega->archivo, $entrega->nombre_archivo);
    }

    public function revisar(Request $request, Entrega $entrega)
    {
        if (!Auth::user()->esAdministrador() && !Auth::user()->esInstructor()) {
            abort(403);
        }

        $request->validate([
            'estado'      => 'required|in:aprobada,rechazada',
            'observacion' => 'nullable|string|max:1000',
        ]);

        $entrega->update([
            'estado'         => $request->estado,
            'observacion'    => $request->observacion,
            'revisado_por'   => Auth::id(),
            'fecha_revision' => now(),
        ]);

        // Si es aprobada, marcar el pivot como completada
        if ($request->estado === 'aprobada') {
            $entrega->aprendiz->actividades()
                ->updateExistingPivot($entrega->actividad_id, ['estado' => 'completada']);
        }

        return redirect()->route('evidencias.show', $entrega)
            ->with('success', 'Evidencia ' . ($request->estado === 'aprobada' ? 'aprobada' : 'rechazada') . ' correctamente.');
    }

    public function destroy(Entrega $entrega)
    {
        if (!Auth::user()->esAdministrador() && !Auth::user()->esInstructor()) {
            abort(403);
        }

        if (Storage::disk('public')->exists($entrega->archivo)) {
            Storage::disk('public')->delete($entrega->archivo);
        }

        $entrega->delete();

        return redirect()->route('evidencias.index')
            ->with('success', 'Evidencia eliminada correctamente.');
    }

    private function verificarAccesoEntrega(Entrega $entrega): void
    {
        $user = Auth::user();
        if ($user->esAprendiz()) {
            $aprendiz = $user->aprendiz;
            if (!$aprendiz || $entrega->aprendiz_id !== $aprendiz->id) {
                abort(403, 'No tienes acceso a esta evidencia.');
            }
        }
        // Instructor: solo ve evidencias de sus fichas
        if ($user->esInstructor()) {
            $fichas     = $user->fichas ?? [];
            $aprendiz   = $entrega->aprendiz;
            if (!in_array($aprendiz->ficha, $fichas) && !$user->esAdministrador()) {
                abort(403, 'No tienes acceso a esta evidencia.');
            }
        }
    }
}
