<?php

namespace app\Core;

class View
{
    public static function render(string $template, array $data = [], string $layout = 'layout')
    {
        $templatePath = __DIR__ . "/../Views/{$template}.php";
        $layoutPath = __DIR__ . "/../Views/{$layout}.php";

        extract($data);

        ob_start();
        include $templatePath;
        $content = ob_get_clean();

        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            echo $content;
        }
    }
}
