<?php

use App\Controllers\Api\AuthController;
use App\Controllers\Api\FoldersController;
use Core\Router;

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

Router::add('api/folders', [
    'controller' => FoldersController::class,
    'action' => 'index',
    'method' => 'GET'
]);

Router::add('api/folders/{id:\d+}', [
    'controller' => FoldersController::class,
    'action' => 'show',
    'method' => 'GET'
]);

Router::add('api/folders/store', [
    'controller' => FoldersController::class,
    'action' => 'store',
    'method' => 'POST'
]);

Router::add('api/folders/{id:\d+}/update', [
    'controller' => FoldersController::class,
    'action' => 'update',
    'method' => 'PUT'
]);

Router::add('api/folders/{id:\d+}/destroy', [
    'controller' => FoldersController::class,
    'action' => 'destroy',
    'method' => 'DELETE'
]);

