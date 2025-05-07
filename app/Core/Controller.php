<?php
namespace app\Core;

use app\Core\Database;
use app\Core\View;

class Controller
{
    protected \PDO $db;
    
    public function __construct()
    {
        $this->db = Database::connect();
    }

    protected array $middlewares = [];

    public function registerMiddleware(string $middlewareClass, string $method = 'handle')
    {
        $this->middlewares[] = ['class' => $middlewareClass, 'method' => $method];
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    protected function view(string $view, array $params = [])
    {
        View::render($view, $params);
    }

    
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit();
    }
}