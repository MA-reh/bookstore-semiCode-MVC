<?php

class Controller
{
    protected function view(string $viewPath, mixed $data = [])
    {
        extract($data);

        require_once __DIR__ . "/../views/{$viewPath}.php";
    }

    public static function views(string $viewPath, mixed $data = [])
    {
        extract($data);

        require_once __DIR__ . "/../views/{$viewPath}.php";
    }
}
