<?php

require_once 'db.php';
require_once 'helper.php';

class Router
{
    private $routes = [];

    public function add($route, $method, $callback)
    {
        $route = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_]+)', $route);
        $route = '#^' . $route . '$#';
        $this->routes[] = ['route' => $route, 'method' => $method, 'callback' => $callback];
    }

    public function run()
    {
        $request = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($method === $route['method'] && preg_match($route['route'], $request, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                if (is_callable($route['callback'])) {
                    call_user_func($route['callback'], $params);
                } elseif (is_string($route['callback'])) {
                    $this->invokeController($route['callback'], $params);
                }
                return;
            }
        }

        // Default 404 response if no route matches
        http_response_code(404);
        echo "404 Not Found";
    }

    private function invokeController($callback, $params)
    {
        list($controller, $method) = explode('@', $callback);
        $controller = "{$controller}.php";

        if (file_exists($controller)) {
            require $controller;

            $controllerClass = basename($controller, '.php');
            $controllerObject = new $controllerClass();

            // Verifica si el método requiere parámetros
            if (empty($params)) {
                $controllerObject->$method();
            } else {
                call_user_func_array([$controllerObject, $method], $params);
            }
        } else {
            http_response_code(500);
            echo "Controller not found";
        }
    }
}
