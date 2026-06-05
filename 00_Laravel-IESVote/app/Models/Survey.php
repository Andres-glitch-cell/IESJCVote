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

    protected $fillable = [
        'title',
        'description',
        'type',
        'max_selections',
        'is_active',
        'is_real_time_enabled',
        'is_anonymous',
        'allowed_roles',  // ✅ añadido
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_real_time_enabled' => 'boolean',
        'is_anonymous' => 'boolean',
        'max_selections' => 'integer',
        'allowed_roles' => 'array',
    ];

    /**
     * * Relación: Una encuesta tiene muchas opciones
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    /**
     * * Helper: ¿Esta encuesta usa categorías?
     * Tipos B (single_cat) y D (multiple_cat) agrupan las opciones por categoría.
     */
    public function hasCategories(): bool
    {
        return in_array($this->type, ['single_cat', 'multiple_cat']);
    }

    /**
     * * Helper: ¿Esta encuesta permite múltiples selecciones?
     * Tipos C (multiple) y D (multiple_cat) permiten más de 1 selección.
     */
    public function isMultiple(): bool
    {
        return in_array($this->type, ['multiple', 'multiple_cat']);
    }
}
