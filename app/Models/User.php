<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
