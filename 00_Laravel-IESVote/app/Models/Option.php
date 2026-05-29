<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    use HasFactory;

    // Campos obligatorios para permitir la inserción de datos masiva
    protected $fillable = ['survey_id', 'option_text'];

    /**
     * RELACIÓN INVERSA: Cada opción pertenece a una única encuesta
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }
}
