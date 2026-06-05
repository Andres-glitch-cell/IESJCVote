<?php

namespace App\Models; // Define la carpeta donde se guarda este archivo (App/Models)

use Illuminate\Database\Eloquent\Factories\HasFactory; // Importa la herramienta para crear datos de prueba
use Illuminate\Database\Eloquent\Model; // Importa la clase base de Eloquent para controlar la base de datos

class VoteRecorded extends Model
{
    use HasFactory; // Activa el uso de fábricas de datos (factories) para pruebas rápidas (crear usuarios falsos, encuestas de prueba, etc.)

    // IMPORTANT Al poner esta línea aseguramos y confirmamos exactamente el nombre de la tabla en la base de datos.
    protected $table = 'vote_recordeds';

    protected $fillable = [
        'user_id',   // ID del usuario que ha votado (quién vota)
        'survey_id', // ID de la encuesta en la que está participando (dónde vota)
        'option_id', // ID de la opción exacta que ha seleccionado (qué vota)
        'vote_hash', // Un código de seguridad encriptado para verificar que el voto es real y no está duplicado (cod_hashed)
    ];

    /**
     * * Define la relación: "Este registro de voto pertenece a una encuesta"
     */
    public function survey()
    {
        // # Conecta el 'survey_id' de esta fila con su encuesta original
        return $this->belongsTo(Survey::class);
    }

    /**
     * * Define la relación: "Este registro de voto pertenece a un usuario"
     */
    public function user()
    {
        // ? Conecta el 'user_id' de esta fila con el usuario que hizo clic
        return $this->belongsTo(User::class);
    }


    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
