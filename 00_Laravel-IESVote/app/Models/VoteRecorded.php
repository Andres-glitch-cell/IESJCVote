<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ! ══════════════════════════════════════════════════════════════════
 * ! MODELO DE REGISTRO DE VOTOS
 * ! ══════════════════════════════════════════════════════════════════
 */
class VoteRecorded extends Model
{
    use HasFactory;
    protected $table = 'vote_recordeds';

    protected $fillable = ['user_id', 'survey_id', 'vote_hash', 'option_id', 'created_at', 'updated_at'];

    /**
     * ? Relación: Un voto pertenece a una encuesta.
     */
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * * Relación: Un voto pertenece a un usuario (¡Importante añadirla!).
     * Al tener esta relación, podrás hacer: $vote->user->name
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
