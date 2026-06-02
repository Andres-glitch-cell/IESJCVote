<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ! ══════════════════════════════════════════════════════════════════
 * ! MODELO DE OPCIONES DE ENCUESTA
 * ! ══════════════════════════════════════════════════════════════════
 */
class Option extends Model
{
    use HasFactory;

    // Campos permitidos para asignación masiva
    protected $fillable = ['survey_id', 'option_text', 'votes'];

    /**
     * ? Definición de tipos (Casts)
     * Esto asegura que Laravel trate los votos siempre como un número entero (int)
     * y el ID como tal, evitando problemas al sumar votos.
     */
    protected $casts = [
        'votes' => 'integer',
        'survey_id' => 'integer',
    ];

    /**
     * * Relación: Una opción pertenece a una encuesta
     */
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
