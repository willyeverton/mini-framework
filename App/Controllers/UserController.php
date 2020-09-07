<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function list($get)
    {
        try {
            // atributo criado dinamicamente.
            $this->view->pagination = User::paginate($get['page'] ?? 1);

            $this->render('list');

        } catch (\Throwable $throwable) {
            $this->render('list', $throwable->getMessage());
        }
    }
}