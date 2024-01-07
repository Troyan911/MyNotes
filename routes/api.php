<?php

use App\Controllers\Api\AuthController;
use App\Controllers\Api\FoldersController;
use App\Controllers\Api\NotesController;
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

Router::add('api/notes', [
    'controller' => NotesController::class,
    'action' => 'index',
    'method' => 'GET'
]);

Router::add('api/notes/{id:\d+}', [
    'controller' => NotesController::class,
    'action' => 'show',
    'method' => 'GET'
]);

Router::add('api/notes/store', [
    'controller' => NotesController::class,
    'action' => 'store',
    'method' => 'POST'
]);

Router::add('api/notes/{id:\d+}/update', [
    'controller' => NotesController::class,
    'action' => 'update',
    'method' => 'PUT'
]);

Router::add('api/notes/{id:\d+}/destroy', [
    'controller' => NotesController::class,
    'action' => 'destroy',
    'method' => 'DELETE'
]);