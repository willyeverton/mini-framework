<?php

namespace App\Controllers;

abstract class Controller
{
    protected $views;
    private $action;

    public function __construct()
    {
        // stdClass permite criar atributos pra classes em tempo de exeção
        $this->views = new \stdClass;
    }

    protected function render($action, $layout = 'layout')
    {
        $this->action = $action;
        if ($layout && file_exists("../App/Views/$layout.phtml")){
            include_once "../App/Views/layout.phtml";
        }else{
            $this->content();
        }
    }

    protected function content()
    {
        $current = get_class($this);
        $singleClassName = strtolower((str_replace("Controller", "", str_replace("App\\Controllers\\", "", $current))));
        include_once "../App/Views/".$singleClassName."/".$this->action.".phtml";
    }
}