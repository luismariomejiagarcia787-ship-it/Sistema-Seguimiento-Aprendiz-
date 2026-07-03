<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorFicha extends Model
{
    protected $table = 'instructor_ficha';
    protected $fillable = ['user_id', 'ficha'];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fichaModelo()
    {
        return $this->belongsTo(Ficha::class, 'ficha', 'numero');
    }
}
