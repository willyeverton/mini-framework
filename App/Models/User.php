<?php

namespace App\Models;

class User extends Model
{
    protected $table = "users";

    public function __construct()
    {
        parent::__construct($this->table);
    }
}