<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    /**
     * Campos habilitados para la asignación masiva.
     */
    protected $fillable = [
        'survey_id',    // ID de la encuesta a la que pertenece
        'category_id',  // ID de la categoría si aplica, o null
        'option_text',  // El texto de la opción
        'votes',        // Número de votos (añadido en migración posterior)
        'vote_hash',    // Hash del voto (añadido en migración posterior)
        'option_id',    // ID de la opción (añadido en migración posterior)
    ];

    /**
     * Conversión de tipos automática.
     */
    protected $casts = [
        'survey_id' => 'integer',
        'category_id' => 'integer',
    ];

    /**
     * Relación: Una opción pertenece a una encuesta.
     */
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * Relación opcional: Una opción puede estar vinculada a un colectivo específico.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación: Una opción puede recibir muchos votos registrados.
     */
    public function votes()
    {
        return $this->hasMany(VoteRecorded::class);
    }
}