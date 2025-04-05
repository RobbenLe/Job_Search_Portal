<?php

namespace App\Services;

class ResponseService
{
    static function Send($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }

    static function Error($error = "An error occurred", $status = 500)
    {
        self::Send(["error" => $error], $status);
    }

    static function Success($message = "OK", $data = [], $status = 200)
    {
        self::Send([
            "success" => true,
            "message" => $message,
            "data" => $data
        ], $status);
    }

    static function SetCorsHeaders()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
}
