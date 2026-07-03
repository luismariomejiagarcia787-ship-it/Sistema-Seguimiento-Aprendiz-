<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    use HasFactory;

    protected $table = 'observaciones';
    protected $fillable = ['aprendiz_id', 'instructor_id', 'contenido', 'tipo'];

    public function aprendiz()
    {
        return $this->belongsTo(Aprendiz::class, 'aprendiz_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
