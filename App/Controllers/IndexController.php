<?php

namespace App\Controllers;

use App\Models\User;

class IndexController extends Controller
{
    public function Index()
    {
        $user = new User();
        // atributo criado dinamicamente.
        $this->views->listUsers = $user->fetchAll();

        $this->render("index");
    }

}