<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Category;
use App\Models\VoteRecorded;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos habilitados para la asignación masiva.
     */
    protected $fillable = [
        'username',
        'dni',
        'is_admin',
        'password',
    ];

    /**
     * Los atributos que deben estar ocultos por seguridad.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de tipos automática.
     */
    protected $casts = [
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Helper para comprobar permisos de administrador.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Relación: Historial de votos registrados.
     */
    public function voteRecords()
    {
        return $this->hasMany(VoteRecorded::class);
    }

    /**
     * Relación con las categorías o colectivos del instituto.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_user');
    }
}
