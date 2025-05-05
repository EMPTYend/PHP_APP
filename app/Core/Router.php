<?php

namespace app\Core;

class Router
{
    private $routes = [];

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch($uri) {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($uri, PHP_URL_PATH);
    
        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }
    
        [$controllerClass, $methodName] = $this->routes[$method][$uri];
        
        // УБЕРИТЕ добавление namespace, если используете ::class
        $fullControllerClass = $controllerClass; // Уже содержит полный namespace
        
        error_log("Loading controller: " . $fullControllerClass); // Для отладки
    
        if (!class_exists($fullControllerClass)) {
            error_log("Failed to load: " . $fullControllerClass);
            http_response_code(500);
            echo "500 Internal Server Error: Controller class not found";
            return;
        }
    
        $instance = new $fullControllerClass();
        call_user_func([$instance, $methodName]);
    }
}