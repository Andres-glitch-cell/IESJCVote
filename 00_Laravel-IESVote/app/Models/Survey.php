<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_real_time_enabled' => 'boolean',
        'is_anonymous' => 'boolean',
        'max_selections' => 'integer',
    ];

    // Una encuesta tiene muchas opciones
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    // ¿Esta encuesta agrupa opciones por categoría? (tipos B y D)
    public function hasCategories(): bool
    {
        return in_array($this->type, ['single_cat', 'multiple_cat']);
    }

    // ¿Esta encuesta permite múltiples selecciones? (tipos C y D)
    public function isMultiple(): bool
    {
        return in_array($this->type, ['multiple', 'multiple_cat']);
    }
}
