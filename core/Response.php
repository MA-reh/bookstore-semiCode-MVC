<?php

class Response
{
    public static function error(string $msg, int $status = 404): void
    {
        http_response_code($status);

        echo $msg;
        exit;
    }

    public static function json(array $data, string $msg = "", int $status = 200)
    {
        header('Content-Type: application/json; charset=UTF-8');

        http_response_code($status);

        echo json_encode([
            "message" => $msg,
            "data" => $data,
            "status" => $status,
        ]);
        exit;
    }
}
