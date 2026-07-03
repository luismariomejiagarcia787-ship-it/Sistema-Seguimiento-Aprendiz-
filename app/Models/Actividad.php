<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividades';
    protected $fillable = [
        'titulo', 'descripcion', 'instructor_id', 'fecha_limite',
        'estado', 'porcentaje_peso', 'ficha_asignada',
    ];

    protected $casts = [
        'fecha_limite'    => 'date',
        'porcentaje_peso' => 'integer',
    ];

    // ── Relaciones ──────────────────────────────────────────────

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function aprendices()
    {
        return $this->belongsToMany(Aprendiz::class, 'actividad_aprendiz', 'actividad_id', 'aprendiz_id')
                    ->withPivot('estado')
                    ->withTimestamps();
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'actividad_id');
    }

    // ── Accessors ──────────────────────────────────────────────

    public function getEstadoBadgeAttribute(): string
    {
        $map = [
            'pendiente'  => ['bg' => '#fef3c7', 'color' => '#92400e', 'label' => 'Pendiente'],
            'en_proceso' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'label' => 'En Proceso'],
            'completada' => ['bg' => '#d1fae5', 'color' => '#065f46', 'label' => 'Completada'],
            'retrasada'  => ['bg' => '#fee2e2', 'color' => '#991b1b', 'label' => 'Retrasada'],
        ];
        $s = $map[$this->estado] ?? ['bg' => '#e5e7eb', 'color' => '#6b7280', 'label' => ucfirst($this->estado)];
        return "<span class='badge' style='background:{$s['bg']};color:{$s['color']};'>{$s['label']}</span>";
    }

    public function estaVencida(): bool
    {
        return $this->fecha_limite->isPast() && $this->estado !== 'completada';
    }
}
