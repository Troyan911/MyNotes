<?php

define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR . "/config/constants.php";
require_once BASE_DIR . "/vendor/autoload.php";

//dd(TEST);

use Core\Router;

try {
    $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(BASE_DIR);
//    $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);
    $dotenv->load();

    dd(\Core\Config::get("db.user"));

    if(!preg_match("/assets/i", $_SERVER['REQUEST_URI'])) {
        Router::dispatch($_SERVER['REQUEST_URI']);
        dd($_SERVER);
    }

} catch (PDOException $exception) {
    dd("PDOException", $exception->getMessage());
} catch (Exception $exception) {
    dd("Exception", $exception->getMessage());
}

phpinfo();
