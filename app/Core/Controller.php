<?php

namespace Core;

class Controller
{
    protected function view($viewPath, $data = [])
    {
        extract($data);
        require_once __DIR__ . '/../Views/' . $viewPath . '.php';
    }
}
