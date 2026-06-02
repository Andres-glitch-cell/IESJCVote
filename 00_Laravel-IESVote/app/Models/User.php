<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * ! ══════════════════════════════════════════════════════════════════
 * ! MODELO DE USUARIO (Censo electoral)
 * ! ══════════════════════════════════════════════════════════════════
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * ? Campos habilitados para la asignación masiva.
     */
    protected $fillable = [
        'name',
        'dni',
        'is_admin',
        'password',
    ];

    /**
     * * Los atributos que deben estar ocultos por seguridad.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * * Conversión de tipos automática.
     */
    protected $casts = [
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * TODO Relación: Un usuario puede tener muchos votos registrados.
     * Esto te permitirá hacer cosas como $user->votes
     */
    public function votes()
    {
        return $this->hasMany(VoteRecorded::class);
    }
}
