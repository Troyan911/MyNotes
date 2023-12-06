<?php

namespace Core;

class Router
{
    protected static array $routes = [], $params = [];
    public static function add(string $route, array $params) : void {
        //todo - prepare route by regexp
        static::$routes[$route] = $params;
    }

    public static function dispatch(string $uri) : void {

    }

}