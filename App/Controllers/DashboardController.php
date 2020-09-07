<?php

namespace App\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // atributo criado dinamicamente.
        $this->view->listUsers = User::all();

        $this->render('dashboard');
    }

}