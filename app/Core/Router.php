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
        
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            
            // Обработка middleware
            foreach ($route['middleware'] ?? [] as $middleware) {
                $this->handleMiddleware($middleware);
            }
        }

        // Проверяем точные совпадения маршрутов
        if (isset($this->routes[$method][$uri])) {
            $this->callAction($this->routes[$method][$uri]);
            return;
        }
        
        // Проверяем параметризованные маршруты
        foreach ($this->routes[$method] as $route => $action) {
            $pattern = $this->convertRouteToPattern($route);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Удаляем полное совпадение
                $this->callAction($action, $matches);
                return;
            }
        }
        
        // Если ничего не найдено
        http_response_code(404);
        echo "404 Not Found";
    }

    protected function handleMiddleware($name)
    {
        switch ($name) {
            case 'csrf':
                (new \app\Core\Middleware\CsrfMiddleware())->handle();
                break;
            case 'auth':
                (new \app\Core\Middleware\AuthMiddleware())->handle();
                break;
            case 'admin':
                (new \app\Core\Middleware\AdminMiddleware())->handle();
                break;
        }
    }
    
    protected function callAction($action, $params = []) {
        [$controllerClass, $methodName] = $action;
        
        if (!class_exists($controllerClass)) {
            error_log("Controller not found: " . $controllerClass);
            http_response_code(500);
            echo "500 Internal Server Error: Controller class not found";
            return;
        }
        
        $instance = new $controllerClass();
        call_user_func_array([$instance, $methodName], $params);
    }
    
    protected function convertRouteToPattern($route) {
        // Заменяем {param} на regex группу
        $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^/]+)', $route);
        return '#^' . $pattern . '$#';
    }
}