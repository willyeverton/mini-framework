<?php

namespace App\Controllers;

use App\Helpers\Session;

abstract class Controller
{
    protected $views;
    private $type = 'private';
    private $action;

    public function __construct()
    {
        // stdClass permite criar atributos pra classes em tempo de exeção
        $this->views = new \stdClass;
    }

    protected function render($action, $layout = 'layout')
    {
        if($this->type == 'private')
            $this->validateLogin();

        @ $this->views->csrf_token = $this->generateTokenCSRF();

        $this->action = $action;
        if (file_exists("../App/Views/layout/$layout.phtml")){
            include_once "../App/Views/layout/$layout.phtml";
        }else{
            $this->content();
        }
    }

    private function content()
    {
        $current = get_class($this);
        $singleClassName = strtolower((str_replace("Controller", "", str_replace("App\\Controllers\\", "", $current))));
        include_once "../App/Views/".$singleClassName."/".$this->action.".phtml";
    }

    private function validateLogin() {
        Session::start();
        $name = md5('seg'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

        if(!$_SESSION['user_logged'] || $_SESSION['session_name'] != $name) {
            $this->redirect("/login");
        }
    }

    private function generateTokenCSRF()
    {
        Session::start();
        $_SESSION['csrf_token'] = 'a5773b446f2ec9458da7a16c2905d4de'; // md5(rand());

        return $_SESSION['csrf_token'];
    }

    protected function setActionPublic() {
        $this->type = 'public';
    }
    protected function isRequestGet() {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }

    protected function redirect($route) {
        header("Location:$route");
    }
}