<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos habilitados para la asignación masiva.
     */
    protected $fillable = [
        'username', // Cambiado de 'name' a 'username' para coincidir con la base de datos
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
     * Relación: Historial de votaciones en las que ha participado este usuario.
     * Sirve para comprobar si ya ha ejercido su derecho al voto en una urna digital.
     */
    public function pollRegisters()
    {
        return $this->hasMany(PollRegister::class);
    }

    /**
     * Relación con las categorías o colectivos del instituto (Alumnos, Profesores, etc).
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_user');
    }
}
