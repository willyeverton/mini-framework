<?php

namespace App\Controllers;

use App\Models\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->setActionPublic();
    }

    public function home()
    {
        $this->render('home', '');
    }
}