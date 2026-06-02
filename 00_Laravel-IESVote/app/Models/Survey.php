<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ! ══════════════════════════════════════════════════════════════════
 * ! MODELO DE ENCUESTAS
 * ! ══════════════════════════════════════════════════════════════════
 */
class Survey extends Model
{
    use HasFactory;

    /**
     * ? Campos permitidos para asignación masiva.
     * Añadimos 'is_active' para que el controlador pueda guardarlo.
     */
    protected $fillable = ['title', 'is_active'];

    /**
     * * Definición de tipos (Casts)
     * Tratamos is_active como un booleano (true/false) automáticamente.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * * Relación: Una encuesta tiene muchas opciones
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
