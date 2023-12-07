<?php

define('BASE_DIR', dirname(__DIR__));
require_once BASE_DIR . "/Config/constants.php";
require_once BASE_DIR . "/vendor/autoload.php";

use Core\Router;

try {

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
