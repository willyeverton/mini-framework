<?php

namespace App\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // atributo criado dinamicamente.
        $this->views->listUsers = User::fetchAll();

        $this->render('dashboard');
    }

}