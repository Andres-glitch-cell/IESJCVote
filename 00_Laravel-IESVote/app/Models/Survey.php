<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    use HasFactory;

    // Campos que permites llenar mediante Survey::create()
    protected $fillable = ['title'];

    /**
     * RELACIÓN: Una encuesta tiene muchas opciones de respuesta
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }
}
