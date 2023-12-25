<?php

use Core\Router;
use \App\Controllers\AuthController;

Router::add('api/auth/registration', [
    'controller' => AuthController::class,
    'action' => 'signup',
    'method' => 'POST'
]);

Router::add('api/auth/login', [
    'controller' => AuthController::class,
    'action' => 'signin',
    'method' => 'POST'
]);