<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'option_text',
        'category',
        'votes',
    ];

    protected $casts = [
        'votes' => 'integer',
        'survey_id' => 'integer',
    ];

    // Una opción pertenece a una encuesta
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
