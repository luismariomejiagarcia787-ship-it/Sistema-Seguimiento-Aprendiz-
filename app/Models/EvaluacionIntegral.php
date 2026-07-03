<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionIntegral extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones_integrales';
    protected $fillable = [
        'aprendiz_id', 'instructor_id',
        'responsabilidad', 'puntualidad', 'trabajo_en_equipo',
        'comunicacion', 'respeto', 'compromiso',
        'liderazgo', 'adaptabilidad', 'autonomia',
        'observaciones',
    ];

    protected $casts = [
        'responsabilidad'  => 'decimal:2',
        'puntualidad'      => 'decimal:2',
        'trabajo_en_equipo'=> 'decimal:2',
        'comunicacion'     => 'decimal:2',
        'respeto'          => 'decimal:2',
        'compromiso'       => 'decimal:2',
        'liderazgo'        => 'decimal:2',
        'adaptabilidad'    => 'decimal:2',
        'autonomia'        => 'decimal:2',
    ];

    public static function criterios(): array
    {
        return [
            'responsabilidad'   => 'Responsabilidad',
            'puntualidad'       => 'Puntualidad',
            'trabajo_en_equipo' => 'Trabajo en Equipo',
            'comunicacion'      => 'Comunicación',
            'respeto'           => 'Respeto',
            'compromiso'        => 'Compromiso',
            'liderazgo'         => 'Liderazgo',
            'adaptabilidad'     => 'Adaptabilidad',
            'autonomia'         => 'Autonomía',
        ];
    }

    public function calcularIndice(): float
    {
        $sum = $this->responsabilidad + $this->puntualidad + $this->trabajo_en_equipo
             + $this->comunicacion + $this->respeto + $this->compromiso
             + $this->liderazgo + $this->adaptabilidad + $this->autonomia;
        return round($sum / 9, 2);
    }

    public function aprendiz()
    {
        return $this->belongsTo(Aprendiz::class, 'aprendiz_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
