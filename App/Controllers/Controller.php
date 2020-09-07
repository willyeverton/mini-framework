<?php

namespace App\Controllers;

use App\Helpers\Session;
use stdClass;

abstract class Controller
{
    protected $view;
    private $type = 'private';
    private $action;

    public function __construct()
    {
        // stdClass permite criar atributos pra classes em tempo de exeção
        $this->view = new stdClass;
    }

    protected function render($action, $layout = 'layout', $message = '', $type = 'danger')
    {
        if($this->type == 'private')
            $this->validateLogin();

        $this->setDefaultVars();
        $this->alert($message, $type);

        $this->action = $action;
        if (file_exists("../App/Views/layout/$layout.phtml")){
            include_once "../App/Views/layout/$layout.phtml";
        } else {
            $this->content();
        }
    }

    private function alert($message, $type)
    {
        $this->view->message = $message ?? '';
        $this->view->type    = $type ?? '';
    }

    private function setDefaultVars()
    {
        $this->view->csrf_token = $this->generateTokenCSRF();
    }

    public function content()
    {
        $current = get_class($this);
        $singleClassName = strtolower((str_replace("Controller", "", str_replace("App\\Controllers\\", "", $current))));
        include_once "../App/Views/".$singleClassName."/".$this->action.".phtml";
    }

    public function pagination($pagination = 'pagination')
    {
        include_once "../App/Views/layout/$pagination.phtml";
    }

    protected function redirect($route)
    {
        header("Location:$route");
    }

    private function validateLogin() {
        Session::start();
        $name = md5('seg'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

        if(!$_SESSION['user_logged'] || $_SESSION['session_name'] != $name){
            $this->redirect("/login");
        }
    }

    private function generateTokenCSRF()
    {
        Session::start();
        $_SESSION['csrf_token'] = '6e7ec13f84277e76daf22cc36a0014ef';//md5(rand());

        return $_SESSION['csrf_token'];
    }

    protected function setActionPublic() {
        $this->type = 'public';
    }

    protected function isRequestGet() {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }
}