<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\User;

class LoginController extends Controller
{
    public function login($request)
    {
        return json_encode($request);
        $this->setActionPublic();

        if($this->isRequestPost())
        {
            $user = new User();
            $response = $user->findByWhere($request);
            
            if($response) {
                $this->setSessionLogged();
                return $this->redirect("/dashboard");
            } 
            $this->views->message = "Usuario e/ou Senha Incorreto(s)";
        }
        $this->render("login", '');
    }

    private function setSessionLogged() {
        Session::start();
        $_SESSION['user_logged'] = true;
    }
}