<?php

namespace App\Controllers;


use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        $this->setActionPublic();
        $this->render("login", '');
    }

    public function login($request)
    {
        $this->setActionPublic();
        $user = new User();
        $response = $user->findByWhere($request);
        var_dump($response);

        if($response)
            $this->setSessionLogged();
    }

    private function setSessionLogged() {
        session_cache_expire(60 * 8);// 8 horas
        session_start();

        $name = md5('seg'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
        session_name($name);

        $_SESSION['session_name'] = $name;
        $_SESSION['user_logged'] = true;
    }
}