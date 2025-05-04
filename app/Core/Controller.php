<?php
namespace app\Core;

use app\Core\Database;

class Controller
{
    protected \PDO $db;
    
    public function __construct()
    {
        $this->db = Database::connect();
    }

    protected function view(string $viewPath, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $fullPath = __DIR__ . '/../../Views/' . $viewPath . '.php';
        
        if (!file_exists($fullPath)) {
            throw new \RuntimeException("View not found: {$viewPath}");
        }
        
        require $fullPath;
    }
    
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit();
    }
}