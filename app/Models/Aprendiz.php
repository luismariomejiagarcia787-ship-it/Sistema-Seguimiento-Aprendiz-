<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aprendiz extends Model
{
    use HasFactory;

    protected $table = 'aprendices';
    protected $fillable = [
        'user_id', 'documento', 'programa_formacion', 'ficha',
        'fecha_inicio', 'estado', 'telefono', 'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
    ];

    // ── Relaciones ──────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fichaModelo()
    {
        return $this->belongsTo(Ficha::class, 'ficha', 'numero');
    }

    public function actividades()
    {
        return $this->belongsToMany(Actividad::class, 'actividad_aprendiz', 'aprendiz_id', 'actividad_id')
                    ->withPivot('estado')
                    ->withTimestamps();
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'aprendiz_id');
    }

    public function observaciones()
    {
        return $this->hasMany(Observacion::class, 'aprendiz_id');
    }

    public function evaluacionesIntegrales()
    {
        return $this->hasMany(EvaluacionIntegral::class, 'aprendiz_id');
    }

    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class, 'aprendiz_id');
    }

    // ── Cálculos académicos (H9, H12) ──────────────────────────

    /**
     * Calcula la definitiva: promedio de notas de todas las actividades
     */
    public function calcularDefinitiva(): float
    {
        $notas = $this->calificaciones()->pluck('nota');
        if ($notas->isEmpty()) return 0.0;
        return round($notas->avg(), 2);
    }

    /**
     * Calcula el Índice Integral promediando evaluaciones de todos los instructores (H14)
     */
    public function calcularIndiceIntegral(): float
    {
        $evals = $this->evaluacionesIntegrales;
        if ($evals->isEmpty()) return 0.0;
        $indices = $evals->map(fn($e) => $e->calcularIndice());
        return round($indices->avg(), 2);
    }

    /**
     * Promedio de cada criterio entre todos los instructores (H14)
     */
    public function promediosCriterios(): array
    {
        $evals = $this->evaluacionesIntegrales;
        if ($evals->isEmpty()) {
            return array_fill_keys(array_keys(EvaluacionIntegral::criterios()), 0.0);
        }
        $result = [];
        foreach (array_keys(EvaluacionIntegral::criterios()) as $criterio) {
            $result[$criterio] = round($evals->avg($criterio), 2);
        }
        return $result;
    }

    // ── Accessors ──────────────────────────────────────────────

    public function getEstadoBadgeAttribute(): string
    {
        $map = [
            'activo'   => ['bg' => '#d1fae5', 'color' => '#065f46', 'label' => 'Activo'],
            'inactivo' => ['bg' => '#f3f4f6', 'color' => '#374151', 'label' => 'Inactivo'],
            'egresado' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'label' => 'Egresado'],
            'retirado' => ['bg' => '#fee2e2', 'color' => '#991b1b', 'label' => 'Retirado'],
        ];
        $s = $map[$this->estado] ?? ['bg' => '#e5e7eb', 'color' => '#6b7280', 'label' => ucfirst($this->estado)];
        return "<span class='badge' style='background:{$s['bg']};color:{$s['color']};'>{$s['label']}</span>";
    }
}
