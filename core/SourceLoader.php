<?php

namespace Core;

require_once dirname(__DIR__) . "/vendor/autoload.php";

try {
    $dotenv = \Dotenv\Dotenv::createUnsafeImmutable(BASE_DIR);
    $dotenv->load();
    $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);
} catch (Exception $exception) {
    dd("Exception", $exception->getMessage());
}
