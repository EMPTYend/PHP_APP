<?php
/**
 * Class Controller
 * 
 * A base controller class that provides common functionality for application controllers.
 * It includes database connection handling, middleware registration, view rendering, 
 * and redirection utilities.
 */
namespace app\Core;

use app\Core\Database;
use app\Core\View;

class Controller
{
    /**
     * @var \PDO $db
     * A PDO instance for database connection.
     */
    protected \PDO $db;

    /**
     * @var array $middlewares
     * An array to store registered middlewares.
     */
    protected array $middlewares = [];

    /**
     * Controller constructor.
     * Initializes the database connection.
     */
    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Registers a middleware for the controller.
     * 
     * @param string $middlewareClass The middleware class name.
     * @param string $method The method to call on the middleware (default: 'handle').
     */
    public function registerMiddleware(string $middlewareClass, string $method = 'handle')
    {
        $this->middlewares[] = ['class' => $middlewareClass, 'method' => $method];
    }

    /**
     * Retrieves the list of registered middlewares.
     * 
     * @return array An array of registered middlewares.
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * Renders a view with optional parameters.
     * 
     * @param string $view The name of the view file.
     * @param array $params An associative array of parameters to pass to the view.
     */
    protected function view(string $view, array $params = [])
    {
        View::render($view, $params);
    }

    /**
     * Redirects the user to a specified URL.
     * 
     * @param string $url The URL to redirect to.
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit();
    }
}