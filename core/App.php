<?php

class App
{
    public static function run()
    {
        session_start();

        $url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $type = (str_starts_with($url, baseUrl . "/api")) ? "api" : "web";

        if ($type == "api") {
            header('Content-Type: application/json; charset=UTF-8');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
        }

        require_once __DIR__ . "/Route.php";
        require_once __DIR__ . "/../app/helpers/Helpers.php";
        require_once __DIR__ . "/Request.php";
        require_once __DIR__ . "/Response.php";
        require_once __DIR__ . "/Validation.php";

        require_once __DIR__ . "/../routes/{$type}.php";

        define('NUMBER_OF_CARDS', 12);

        Route::dispatch();
    }
}
