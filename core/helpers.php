<?php

use Core\Config;
use Core\Db;

function requestBody() : array {
     $data = [];

     $requestBody = file_get_contents("php://input");

     if(!empty($requestBody)) {
         $data = json_decode($requestBody, true);
     }
     return $data;
}

function json_response(int $code = 200, array $data = []): string
{

    header_remove();
    http_response_code($code);
    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    header("Content-Type: application/json");

    $status = [
        200 => "200 OK",
        400 => "400 Bad Request",
        403 => "403 Access Denied",
        422 => "422 Unprocessable Entity",
        500 => "500 Internal server Error"
    ];

    header("Status: " . $status[$code]);

    return json_encode([
        'code' => $code,
        'status' => $status[$code],
        ...$data
    ]);

}

function config(string $name): string|null
{
    return Config::get($name);
}

function db(): PDO
{
    return Db::connect();
}
