<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'rol', 'telefono', 'foto'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    // ── Roles ─────────────────────────────────────────────────────────

    public function esAdministrador(): bool { return $this->rol === 'administrador'; }
    public function esInstructor(): bool    { return $this->rol === 'instructor'; }
    public function esAprendiz(): bool      { return $this->rol === 'aprendiz'; }

    // ── Relaciones ────────────────────────────────────────────────────

    public function aprendiz()
    {
        return $this->hasOne(Aprendiz::class, 'user_id');
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'instructor_id');
    }

    public function fichasAsignadas()
    {
        return $this->hasMany(InstructorFicha::class, 'user_id');
    }

    // ── Fichas del instructor (array de números de ficha) ─────────────

    public function getFichasAttribute(): array
    {
        if (!$this->esInstructor()) return [];
        return $this->fichasAsignadas()->pluck('ficha')->toArray();
    }

    // ── Foto URL ──────────────────────────────────────────────────────

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        $initials = urlencode(substr($this->name, 0, 2));
        return "https://ui-avatars.com/api/?name={$initials}&background=39A900&color=fff&size=80";
    }
}
