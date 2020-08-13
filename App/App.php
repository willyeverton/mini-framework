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
        array_walk($this->routes, function ($route) {

            $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $method = strtolower($_SERVER["REQUEST_METHOD"]);

            if (($url == $route['route'])
                && ($method == $route['method'])) {

                $request = $this->getRequest();
                $this->validateTokenCSRF($request);

                $controller = "App\\Controllers\\{$route['controller']}";
                $action = $route['action'];

                $controller = new $controller();
                $controller->$action($request);
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

    private function validateTokenCSRF(& $request)
    {
        if(isset($request['csrf_token'])) {
            Session::start();

            if ($_SESSION['csrf_token'] != $request['csrf_token'])
                throw new \Exception('csrf_token invalid');
            else
                unset($request['csrf_token']);
        }
    }
}