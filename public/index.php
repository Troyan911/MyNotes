<?php

require_once dirname(__DIR__) . "/core/SourceLoader.php";

//dd(TEST);

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
