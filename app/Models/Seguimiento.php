<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'aprendiz_id', 'instructor_id', 'porcentaje', 'comentario', 'fecha_seguimiento',
    ];

    protected $casts = [
        'fecha_seguimiento' => 'date',
        'porcentaje' => 'float',
    ];

    public function aprendiz()
    {
        return $this->belongsTo(Aprendiz::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function getColorProgresoAttribute(): string
    {
        if ($this->porcentaje >= 80) return 'success';
        if ($this->porcentaje >= 50) return 'primary';
        if ($this->porcentaje >= 25) return 'warning';
        return 'danger';
    }
}
