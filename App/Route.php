<?php

namespace App;

class Route extends App
{
    protected function setRoutes()
    {
        $this->routes[] = array("method" => "get",  "route" => '/', "controller" => "HomeController", "action" => "home");
        $this->routes[] = array("method" => "get",  "route" => '/login', "controller" => "LoginController", "action" => "index");
        $this->routes[] = array("method" => "post", "route" => '/login', "controller" => "LoginController", "action" => "login");
        $this->routes[] = array("method" => "get", "route" => '/dashboard', "controller" => "DashboardController", "action" => "dashboard");
    }
}