<?php

namespace App;

use App\Helpers\Session;

abstract class App
{
    protected $routes;

    public function __construct()
    {
        $this->setRoutes();
        Session::config();
    }

    abstract protected function setRoutes();

    public function run()
    {
        return file_get_contents("php://input");

        array_walk($this->routes, function ($route) {

            $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $method = strtolower($_SERVER["REQUEST_METHOD"]);

            if (($url == $route['route'])
                && ($method == $route['method'])) {

                $this->validateTokenCSRF();

                $controller = "App\\Controllers\\{$route['controller']}";
                $action = $route['action'];

                $controller = new $controller();
                $controller->$action($_POST ?? $_GET);
            }
        });
    }

    private function getRequest() {

        switch ($_SERVER["REQUEST_METHOD"]) {
            case 'GET':
                return $_GET;
            
            case 'POST':
                return $_POST;
            
            default: // PUT or Delete
                return json_decode(file_get_contents("php://input"), true);
        }
    }

    private function validateTokenCSRF()
    {
        if($_POST) {
            Session::start();

            if ($_SESSION['csrf_token'] != $_POST['csrf_token'])
                throw new \Exception('csrf_token invalid');
            else
                unset($_POST['csrf_token']);
        }
    }
}