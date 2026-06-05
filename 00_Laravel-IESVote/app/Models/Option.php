<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Importa la herramienta para fabricar datos ficticios en pruebas
use Illuminate\Database\Eloquent\Model; // Importa la clase base de Eloquent que conecta este archivo con la base de datos

class Option extends Model
{
    use HasFactory; // * Activa las fábricas de datos (factories) para poder hacer pruebas rápidas con este modelo

    // IMPORTANT de seguridad ($fillable): Aquí indicas qué columnas de la tabla 'options' permites que se puedan rellenar de golpe desde un formulario web.
    protected $fillable = [
        'survey_id',   // El ID de la encuesta a la que pertenece esta opción (la clave foránea)
        'option_text', // ? El texto de la respuesta que verá el usuario (ej: "Sí", "No", "Opción A", etc.)
        'category',    // La categoría o grupo al que pertenece la opción (si la encuesta usa categorías)
        'votes',       // El contador de votos acumulados que tiene esta respuesta específica
    ];

    // IMPORTANT Convierte los datos que vienen crudos de la base de datos a formatos nativos de PHP automáticamente al leerlos.
    protected $casts = [
        'votes' => 'integer',     // ? Fuerza a que el contador de votos se trate siempre como un número entero en PHP
        'survey_id' => 'integer', // ? Fuerza a que el ID de la encuesta asociada sea siempre un número entero
    ];


    public function survey()
    {
        // IMPORTANT Le dice a Laravel que busque el 'survey_id' que tiene esta opción para encontrar a qué encuesta exacta pertenece.
        return $this->belongsTo(Survey::class);
    }
}