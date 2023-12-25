<?php

use Core\Router;

Router::add(
    'users',
    [
        'controller' => \App\Controllers\AuthController::class,
        'action' => 'index',
        'method' => 'GET'
    ]
);

Router::add(
    'users/{id:\d+}',
    [
        'controller' => \App\Controllers\AuthController::class,
        'action' => 'show',
        'method' => 'GET'
    ]
);

Router::add(
    'users/{id:\d+}/update',
    [
        'controller' => \App\Controllers\AuthController::class,
        'action' => 'update',
        'method' => 'POST'
    ]
);

Router::add(
    'users/{id:\d+}/edit',
    [
        'controller' => \App\Controllers\AuthController::class,
        'action' => 'edit',
        'method' => 'GET'
    ]
);

Router::add(
    'posts/{post_id:\d+}/comments/{comment_id:\d+}',
    [
        'controller' => \App\Controllers\AuthController::class,
        'action' => 'show',
        'method' => 'POST'
    ]
);

const TEST = 5;