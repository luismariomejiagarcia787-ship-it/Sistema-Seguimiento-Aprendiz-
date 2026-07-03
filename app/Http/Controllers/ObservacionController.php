<?php
namespace App\Http\Controllers;

use App\Models\Observacion;
use App\Models\Aprendiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObservacionController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->esAprendiz()) abort(403);

        $request->validate([
            'aprendiz_id' => 'required|exists:aprendices,id',
            'contenido'   => 'required|string|min:5|max:2000',
            'tipo'        => 'required|in:academica,disciplinaria,general',
        ]);

        $aprendiz = Aprendiz::findOrFail($request->aprendiz_id);

        if ($user->esInstructor() && !in_array($aprendiz->ficha, $user->fichas ?? [])) {
            abort(403, 'No tienes acceso a ese aprendiz.');
        }

        Observacion::create([
            'aprendiz_id'   => $request->aprendiz_id,
            'instructor_id' => $user->id,
            'contenido'     => $request->contenido,
            'tipo'          => $request->tipo,
        ]);

        return back()->with('success', 'Observación registrada correctamente.');
    }

    public function destroy(Observacion $observacion)
    {
        $user = Auth::user();
        if (!$user->esAdministrador() && $observacion->instructor_id !== $user->id) {
            abort(403);
        }
        $observacion->delete();
        return back()->with('success', 'Observación eliminada.');
    }
}
