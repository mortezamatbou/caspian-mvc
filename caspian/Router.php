<?php

namespace Caspian;

class Router {

    private static $route_get = [];
    private static $route_post = [];
    private static $route_delete = [];
    private static $route_put = [];
    private static $route_any = [];
    private static $method = 'GET';
    private static $type = 'api';

    public static function get_route()
    {
        $method = self::get_request_method();
        switch ($method) {
            case 'GET':
                return self::$route_get;
            case 'POST':
                return self::$route_post;
            case 'DELETE':
                return self::$route_delete;
            case 'PUT':
                return self::$route_put;
            case 'ANY':
                return self::$route_any;
            default:
                return self::$route_get;
        }
    }

    public static function list_get()
    {
        return self::$route_get;
    }

    public static function list_post()
    {
        return self::$route_post;
    }

    public static function list_delete()
    {
        return self::$route_delete;
    }

    public static function list_put()
    {
        return self::$route_put;
    }

    public static function list_any()
    {
        return self::$route_any;
    }

    public static function get($route, $action, array $middleware = []): void
    {
        self::$method = 'GET';
        self::checkup($route, $action, $middleware);
    }

    public static function post($route, array $action, array $middleware = []): void
    {
        self::$method = 'POST';
        self::checkup($route, $action, $middleware);
    }

    public static function delete(string $route, string $action, array $middleware = []): void
    {
        self::$method = 'DELETE';
        self::checkup($route, $action, $middleware);
    }

    public static function put(string $route, string $action, array $middleware = []): void
    {
        self::$method = 'PUT';
        self::checkup($route, $action, $middleware);
    }

    public static function any(string $route, string $action, array $middleware = []): void
    {
        self::$method = 'ANY';
        self::checkup($route, $action, $middleware);
    }

    private static function add_route(string $route, array $action, array $middleware = []): void
    {

        switch (self::$method) {
            case 'GET':
                self::$route_get[$route] = ['actions' => $action, 'middleware' => $middleware];
                break;
            case 'POST':
                self::$route_post[$route] = ['actions' => $action, 'middleware' => $middleware];
                break;
            case 'DELETE':
                self::$route_delete[$route] = ['actions' => $action, 'middleware' => $middleware];
                break;
            case 'PUT':
                self::$route_put[$route] = ['actions' => $action, 'middleware' => $middleware];
                break;
            case 'ANY':
                self::$route_any[$route] = ['actions' => $action, 'middleware' => $middleware];
                break;
        }
    }

    private static function checkup(string $route, array $action, array $middleware = [])
    {
        $truncated_route = Tools::trunc_route($route);
        if (!is_array($action) || !preg_match("/^App\\\\Controllers\\\\([A-Za-z][A-Za-z_0-9]*)(\\\\[A-Za-z][A-Za-z_0-9]*)*$/", $action[0]) || !preg_match("/^[A-Za-z][A-Za-z_0-9]*$/", $action[1])) {
            exit("Invalid Route Format");
        }
        self::add_route($truncated_route, $action, $middleware);
    }

    public static function get_request_method()
    {
        $default_method = 'GET';

        $get_request = filter_input(INPUT_GET, '_method', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($get_request) {
            switch (strtoupper($get_request)) {
                case 'GET':
                    return 'GET';
                case 'POST':
                    return 'POST';
                case 'PUT':
                    return 'PUT';
                case 'DELETE':
                    return 'DELETE';
            }
        }

        $post_request = filter_input(INPUT_POST, '_method', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($post_request) {
            switch (strtoupper($post_request)) {
                case 'GET':
                    return 'GET';
                case 'POST':
                    return 'POST';
                case 'PUT':
                    return 'PUT';
                case 'DELETE':
                    return 'DELETE';
            }
        }

        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($method) {
            return $method;
        }

        return $default_method;
    }

    public static function routes()
    {
        $routes = [];
        $routes_of_method = [];
        $list_any = self::list_any();
        $method = self::get_request_method();

        switch ($method) {
            case 'GET':
                $routes_of_method = self::list_get();
                break;
            case 'POST':
                $routes_of_method = self::list_post();
                break;
            case 'PUT':
                $routes_of_method = self::list_put();
                break;
            case 'DELETE':
                $routes_of_method = self::list_delete();
                break;
        }

        if ($routes_of_method) {
            foreach ($routes_of_method as $route => $action) {
                $routes[$route] = $action;
            }
        }

        if ($list_any) {
            foreach ($list_any as $route => $action) {
                $routes[$route] = $action;
            }
        }

        return $routes;
    }
}
