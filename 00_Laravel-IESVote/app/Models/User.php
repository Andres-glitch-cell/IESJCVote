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
        'name',
        'dni',
        'is_admin',
        'password', // 🔐 Añadido para permitir guardar el DNI hasheado aquí
    ];

    /**
     * Conversión de tipos automática.
     */
    protected $casts = [
        'is_admin' => 'boolean',
    ];
}
