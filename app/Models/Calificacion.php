<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';
    protected $fillable = [
        'aprendiz_id', 'actividad_id', 'instructor_id', 'nota', 'observacion',
    ];

    protected $casts = [
        'nota' => 'decimal:2',
    ];

    public function aprendiz()
    {
        return $this->belongsTo(Aprendiz::class, 'aprendiz_id');
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function getColorNotaAttribute(): string
    {
        if ($this->nota >= 8) return 'success';
        if ($this->nota >= 6) return 'primary';
        if ($this->nota >= 4) return 'warning';
        return 'danger';
    }

    public function getNotaFormateadaAttribute(): string
    {
        return number_format($this->nota, 1);
    }
}
