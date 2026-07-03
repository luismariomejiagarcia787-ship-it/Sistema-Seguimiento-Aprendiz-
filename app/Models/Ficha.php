<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero', 'programa_formacion', 'fecha_inicio', 'fecha_fin', 'estado', 'descripcion',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    public function aprendices()
    {
        return $this->hasMany(Aprendiz::class, 'ficha', 'numero');
    }

    public function instructores()
    {
        return $this->hasMany(InstructorFicha::class, 'ficha', 'numero');
    }

    public function getEstadoBadgeAttribute(): string
    {
        $map = [
            'activo'    => ['bg' => '#d1fae5', 'color' => '#065f46', 'label' => 'Activo'],
            'inactivo'  => ['bg' => '#f3f4f6', 'color' => '#374151', 'label' => 'Inactivo'],
            'terminado' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'label' => 'Terminado'],
        ];
        $s = $map[$this->estado] ?? ['bg' => '#e5e7eb', 'color' => '#6b7280', 'label' => ucfirst($this->estado)];
        return "<span class='badge' style='background:{$s['bg']};color:{$s['color']};'>{$s['label']}</span>";
    }
}
