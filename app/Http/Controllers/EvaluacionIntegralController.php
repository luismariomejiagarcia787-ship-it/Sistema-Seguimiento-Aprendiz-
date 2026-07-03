<?php
namespace App\Http\Controllers;

use App\Models\EvaluacionIntegral;
use App\Models\Aprendiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluacionIntegralController extends Controller
{
    public function create(Aprendiz $aprendiz)
    {
        $user = Auth::user();
        if ($user->esAprendiz()) abort(403);

        if ($user->esInstructor() && !in_array($aprendiz->ficha, $user->fichas ?? [])) {
            abort(403);
        }

        $evaluacion = EvaluacionIntegral::where('aprendiz_id', $aprendiz->id)
                                         ->where('instructor_id', $user->id)
                                         ->first();

        $criterios = EvaluacionIntegral::criterios();
        return view('evaluaciones.create', compact('aprendiz', 'evaluacion', 'criterios'));
    }

    public function store(Request $request, Aprendiz $aprendiz)
    {
        $user = Auth::user();
        if ($user->esAprendiz()) abort(403);

        if ($user->esInstructor() && !in_array($aprendiz->ficha, $user->fichas ?? [])) {
            abort(403);
        }

        $rules = [];
        foreach (array_keys(EvaluacionIntegral::criterios()) as $criterio) {
            $rules[$criterio] = 'required|numeric|min:0|max:10';
        }
        $rules['observaciones'] = 'nullable|string|max:1000';
        $request->validate($rules);

        $data = $request->only(array_keys(EvaluacionIntegral::criterios()));
        $data['observaciones']  = $request->observaciones;
        $data['aprendiz_id']    = $aprendiz->id;
        $data['instructor_id']  = $user->id;

        EvaluacionIntegral::updateOrCreate(
            ['aprendiz_id' => $aprendiz->id, 'instructor_id' => $user->id],
            $data
        );

        return redirect()->route('aprendices.show', $aprendiz)
               ->with('success', 'Evaluación integral guardada correctamente.');
    }
}
