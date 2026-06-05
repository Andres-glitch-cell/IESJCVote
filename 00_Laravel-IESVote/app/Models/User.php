<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\VoteRecorded;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',      // ✅ corregido de username a name
        'dni',
        'role',
        'is_admin',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function voteRecords()
    {
        return $this->hasMany(VoteRecorded::class);
    }
}