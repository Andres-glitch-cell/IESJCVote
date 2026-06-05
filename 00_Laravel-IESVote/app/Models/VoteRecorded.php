<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteRecorded extends Model
{
    use HasFactory;

    protected $table = 'vote_recordeds';

    protected $fillable = [
        'user_id',
        'survey_id',
        'option_id',
        'vote_hash',
    ];

    // Un voto pertenece a una encuesta
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    // Un voto pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un voto pertenece a una opción
    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
