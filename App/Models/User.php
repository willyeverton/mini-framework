<?php

namespace App\Models;

class User extends Model
{
    protected $table = "users";
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'active',
        'admin',
    ];

    public function seller()
    {
        return true;
    }
}