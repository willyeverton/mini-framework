<?php

namespace App\Controllers;


use App\Helpers\Session;
use App\Models\User;

class LoginController extends Controller
{

    public function login($request)
    {
        $this->setActionPublic();

        if(!$this->isRequestGet()) {

            $request['password'] = md5($request['password']);

            $response = (new User)->where($request)->get();

            if($response) {
                $this->setSessionLogged();
                $this->redirect("/dashboard");
            } else {
                @ $this->views->message = "E-mail ou Senha Incorreto!";
            }
        }
        $this->render("login", '');
    }

    public function logout() {

        Session::destroy();
        $this->redirect("/dashboard");
    }

    private function setSessionLogged() {

        Session::start();
        $_SESSION['user_logged'] = true;
    }
}