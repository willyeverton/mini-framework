<?php

namespace App;

abstract class App
{
    protected $routes;

    public function __construct()
    {
        $this->setRoutes();
    }

    abstract protected function setRoutes();

    public function run()
    {
        array_walk($this->routes, function ($route) {

            $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $method = strtolower($_SERVER["REQUEST_METHOD"]);

            if (($url == $route['route'])
                && ($method == $route['method'])) {

                $this->validateTokenCSRF();

                $controller = "App\\Controllers\\{$route['controller']}";
                $action = $route['action'];

                $controller = new $controller();
                $controller->$action($_GET ?? $_POST);
            }
        });
    }

    private function validateTokenCSRF()
    {
        if($_POST) {
            session_start();

            if ($_SESSION['csrf_token'] != $_POST['csrf_token'])
                throw new \Exception('csrf_token invalid');
            else
                unset($_POST['csrf_token']);
        }
    }
}