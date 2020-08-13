<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function list()
    {
        // atributo criado dinamicamente.
        $this->views->users = User::fetchAll();

        $this->render('list');
    }
}