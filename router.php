<?php

class Router
{
    private $routes = [];

    public function add($route, $method, $callback)
    {
        $this->routes[] = ['route' => $route, 'method' => $method, 'callback' => $callback];
    }

    public function run()
    {
        $request = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['route'] === $request && $route['method'] === $method) {
                if (is_callable($route['callback'])) {
                    call_user_func($route['callback']);
                } elseif (is_string($route['callback'])) {
                    $this->invokeController($route['callback']);
                }
                return;
            }
        }

        // Default 404 response if no route matches
        http_response_code(404);
        echo "404 Not Found";
    }

    private function invokeController($callback)
    {
        list($controller, $method) = explode('@', $callback);
        $controller = "{$controller}.php";

        if (file_exists($controller)) {
            require $controller;
            $controllerClass = basename($controller, '.php');
            $controllerObject = new $controllerClass();
            require_once 'db.php';
            $controllerObject->$method();
        } else {
            http_response_code(500);
            echo "Controller not found";
        }
    }
}
