<?php

namespace App\Models; // Especifica que este archivo vive en la carpeta App/Models

use Illuminate\Database\Eloquent\Factories\HasFactory; // Herramienta para generar usuarios de prueba
use Illuminate\Foundation\Auth\User as Authenticatable; // Permite que este modelo sirva para iniciar sesión (Login)
use Illuminate\Notifications\Notifiable; // Activa el sistema para poder enviar notificaciones al usuario
use App\Models\VoteRecorded; // Importa el modelo de los votos registrados para poder relacionarlos

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Activa las funciones de fábrica de pruebas y notificaciones

    // * Tabla users
    protected $fillable = [
        'name',     // Nombre completo del usuario
        'dni',      // Documento de identidad (DNI/NIE)
        'role',     // El rol del usuario (ej: 'alumno', 'profesor', 'directiva')
        'is_admin', // Si tiene permisos totales de administrador
        'password', // La contraseña de acceso
    ];

    // IMPORTANT Columnas que Laravel ocultará automáticamente cuando se consulte la información del usuario, para que nunca se filtren por error.
    protected $hidden = [
        'password',       // Oculta la contraseña encriptada
        'remember_token', // Oculta el token de la opción "recuérdame" del login
    ];
    // # Transforma los datos de la base de datos  a formatos nativos de PHP automáticamente para que sea seguro trabajar con ellos.
    protected $casts = [
        'is_admin' => 'boolean', // Convierte el 1 o 0 de la base de datos en true o false
        'password' => 'hashed',  // Encripta la contraseña automáticamente cuando se guarda en la base de datos
    ];


    public function isAdmin(): bool
    {
        // Comparamos si el valor de la columna 'is_admin' de este usuario es exactamente igual a true.
        if ($this->is_admin === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * * Define la relación: "Un usuario puede tener muchos votos registrados"
     * * Conecta este modelo con el modelo VoteRecorded.
     */
    public function voteRecords()
    {
        // [IMPORTANT] Indica que un usuario es dueño de los registros de votación que dejen su huella en el sistema
        return $this->hasMany(VoteRecorded::class);
    }
}