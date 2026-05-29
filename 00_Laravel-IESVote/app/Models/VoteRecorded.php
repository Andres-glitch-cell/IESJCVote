<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoteRecorded extends Model
{
    use HasFactory;

    // Nombre de la tabla explícito por convención de Laravel
    protected $table = 'vote_recordeds';

    // Campos habilitados para asignación masiva
    protected $fillable = ['user_id', 'survey_id', 'vote_hash'];
}