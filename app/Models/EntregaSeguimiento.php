<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntregaSeguimiento extends Model
{
    protected $table = 'seguimientos';

    protected $fillable = [
        'aprendiz_id',
        'actividad_id',
        'estado',
        'observaciones'
    ];
}