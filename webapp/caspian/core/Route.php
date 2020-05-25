<?php

/**
 * Description of Route
 *
 * @author Morteza Matbou
 */
class Route {

    private static $route_get = [];
    private static $route_post = [];
    private static $route_delete = [];
    private static $route_put = [];
    private static $route_any = [];
    private static $method = 'GET';

    public static function get_route() {
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

    public static function list_get() {
        return self::$route_get;
    }

    public static function list_post() {
        return self::$route_post;
    }

    public static function list_delete() {
        return self::$route_delete;
    }

    public static function list_put() {
        return self::$route_put;
    }
    
    public static function list_any() {
        return self::$route_any;
    }

    public static function get($route, $action = '', $middleware = '') {
        self::$method = 'GET';
        if (is_array($route)) {
            self::generate_array($route, $middleware);
        } else {
            self::a_route($route, $action, $middleware);
        }
    }

    public static function post($route, $action = '', $middleware = '') {
        self::$method = 'POST';
        if (is_array($route)) {
            self::generate_array($route, $middleware);
        } else {
            self::a_route($route, $action, $middleware);
        }
    }

    public static function delete($route, $action = '', $middleware = '') {
        self::$method = 'DELETE';
        if (is_array($route)) {
            self::generate_array($route, $middleware);
        } else {
            self::a_route($route, $action, $middleware);
        }
    }

    public static function put($route, $action = '', $middleware = '') {
        self::$method = 'PUT';
        if (is_array($route)) {
            self::generate_array($route, $middleware);
        } else {
            self::a_route($route, $action, $middleware);
        }
    }

    public static function any($route, $action = '', $middleware = '') {
        self::$method = 'ANY';
        if (is_array($route)) {
            self::generate_array($route, $middleware);
        } else {
            self::a_route($route, $action, $middleware);
        }
    }

    private static function add_route($route, $action, $middleware = '') {
        $route = str_replace('/', '\/', trim($route));
        $action = trim($action);
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

    private static function a_route($route, $action, $middleware = '') {
        $route = trim($route);
        $action = trim($action);
        
        if ($route == '' || $route == '/') {
            self::add_route('welcome_page', $action, $middleware);
            return;
        }
        self::add_route($route, $action, $middleware);
    }


    private static function generate_array($routes, $middleware = '') {

        $holder = array();

        foreach ($routes as $route => $action) {
            if (is_array($action)) {

                foreach ($action as $key => $value) {
                    $index_name = $key ? $route . '/' . $key : $route;
                    if (is_array($value)) {
                        $holder[$index_name] = $value;
                    } else {
                        self::a_route($index_name, $value, $middleware);
                    }
                }
            } else {
                self::a_route($route, $action);
            }
        }

        if ($holder) {
            $h = [];
            foreach ($holder as $k => $v) {
                foreach ($v as $k2 => $v2) {
                    $index_name = $k2 ? $k . '/' . $k2 : $k;
                    $h[$index_name] = $v2;
                }
            }
            if ($h) {
                self::generate_array($h);
            }
        }
    }

    public static function get_request_method() {
        $default_method = 'GET';

        $get_request = filter_input(INPUT_GET, '_method', FILTER_SANITIZE_STRIPPED);
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

        $post_request = filter_input(INPUT_POST, '_method', FILTER_SANITIZE_STRIPPED);

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

        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRIPPED);
        if ($method) {
            return $method;
        }

        return $default_method;
    }

    public static function routes() {
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
