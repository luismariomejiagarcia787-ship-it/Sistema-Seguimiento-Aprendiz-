<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    protected $fillable = [
        'aprendiz_id', 'actividad_id', 'archivo', 'nombre_archivo',
        'tipo_archivo', 'estado', 'observacion', 'revisado_por', 'fecha_revision',
    ];

    protected $casts = [
        'fecha_revision' => 'datetime',
    ];

    // ── Relaciones ──────────────────────────────────────────────

    public function aprendiz()
    {
        return $this->belongsTo(Aprendiz::class, 'aprendiz_id');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }

    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    // ── Accessors ──────────────────────────────────────────────

    public function getEstadoBadgeAttribute(): string
    {
        $map = [
            'en_revision' => ['bg' => '#fef3c7', 'color' => '#92400e', 'label' => 'En Revisión'],
            'aprobada'    => ['bg' => '#d1fae5', 'color' => '#065f46', 'label' => 'Aprobada'],
            'rechazada'   => ['bg' => '#fee2e2', 'color' => '#991b1b', 'label' => 'Rechazada'],
        ];
        $s = $map[$this->estado] ?? ['bg' => '#e5e7eb', 'color' => '#6b7280', 'label' => ucfirst(str_replace('_', ' ', $this->estado))];
        return "<span class='badge' style='background:{$s['bg']};color:{$s['color']};'>{$s['label']}</span>";
    }

    public function getIconoArchivoAttribute(): string
    {
        $ext = strtolower(pathinfo($this->nombre_archivo, PATHINFO_EXTENSION));
        return match($ext) {
            'pdf'          => 'bi-file-earmark-pdf-fill text-danger',
            'doc', 'docx'  => 'bi-file-earmark-word-fill text-primary',
            'xls', 'xlsx'  => 'bi-file-earmark-excel-fill text-success',
            'ppt', 'pptx'  => 'bi-file-earmark-ppt-fill text-warning',
            'jpg','jpeg','png','gif','webp' => 'bi-file-earmark-image-fill text-info',
            'zip', 'rar'   => 'bi-file-earmark-zip-fill text-secondary',
            default        => 'bi-file-earmark-fill text-muted',
        };
    }
}
