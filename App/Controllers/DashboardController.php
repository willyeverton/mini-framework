<?php

namespace App\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $this->setActionPublic();
        $user = new User();
        // atributo criado dinamicamente.
        $this->views->listUsers = $user->fetchAll();

        $this->render('dashboard');
    }

}