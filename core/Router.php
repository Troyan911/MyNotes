<?php

namespace Core;

class Router
{
    protected static array $routes = [];
    protected static array $params = [];
    protected static array $convertTypes = [
        'd' => 'int',
        '.' => 'string'
    ];

    public static function add(string $route, array $params): void
    {
//        d($route);
        $route = preg_replace("/\//", "\\/", $route);
        $route = preg_replace('/\{([a-z_]+):([^}]+)}/', '(?P<$1>$2)', $route);
        $route = "/^$route$/i";
        static::$routes[$route] = $params;
    }

    public static function dispatch(string $uri): string
    {
        $data = [];
        $uri = static::removeQueryVariables($uri);
        $uri = trim($uri, '/');

        if (static::match($uri)) {
            //check HTTP method
            static::checkRequestMethod();

            //get controller
            $controller = static::getController();
            $action = static::getAction($controller);

            if ($controller->before($action, static::$params)) {
//                dd($controller, $action, static::$params);
                $response = call_user_func_array([$controller, $action], static::$params);
                $controller->after($action);
            }
        }

        return json_response($response['code'], [
            'data'=>$response['body'],
            'errors'=>$response['errors'],
        ]);
    }

    protected static function getController(): Controller
    {
        $controller = static::$params['controller'] ?? null;

        if (!class_exists($controller)) {
            throw new \Exception("Controller [$controller] doesn't exists", 404);
        }

        unset(static::$params['controller']);
        return new $controller;
    }

    protected static function getAction(Controller $controller): string
    {
        $action = static::$params['action'] ?? null;
        if (!method_exists($controller, $action)) {
            throw new \Exception(get_class($controller) . " doesn't contain action [$action]", 404);
        }
        unset(static::$params['action']);
        return $action;
    }

    protected static function removeQueryVariables(string $uri): string
    {
        return preg_replace('/([\w\/\-]+)\?([\w\d\s\/=%*&\?]+)/i', '$1', $uri);
    }

    protected static function match(string $uri): bool
    {
        foreach (static::$routes as $route => $params) {
            if (preg_match($route, $uri, $matches)) {
                static::$params = static::buildParams($route, $matches, $params);
                return true;
            }
        }
        throw new \Exception("Route [$uri] not found", 404);
    }

    protected static function buildParams(string $route, array $matches, array $params): array
    {
        preg_match_all('/\(\?P<[\w]+>(\\\\)?([\w\.][\+]*)\)/', $route, $types);
        $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        if (!empty($types)) {
            $lastKey = array_key_last($types);

            $types[$lastKey] = array_map(fn($item) => str_replace("+", "", $item,), $types[$lastKey]);

            $step = 0;
            foreach ($matches as $name => $match) {
                settype($match, static::$convertTypes[$types[$lastKey][$step]]);
                $params[$name] = $match;
                $step++;
            }
        }
//        d($types, $matches);
//        d($params);
        return $params;
    }

    private static function checkRequestMethod()
    {
        if (array_key_exists('method', static::$params)) {
            $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
            if ($requestMethod !== strtolower(static::$params['method'])) {
                //todo add custom exception
                throw new \Exception("Method [$requestMethod] is not allowed for this route", 404);
            }
        }
        unset(self::$params['method']);
    }

}