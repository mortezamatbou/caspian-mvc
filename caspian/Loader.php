<?php

namespace Caspian;

use \Caspian\Route;
use \App\Registers\RouteRegister;

class Loader {

    private string $uri;
    private string $path;
    private string $route_path;
    private string $query;
    private array $segments;
    public array $routes;
    public $url_segments = [];
    private RouteRegister $register;
    private Route $route;

    function __construct()
    {
        $this->uri = $this->get_request_uri();

        $uri = Tools::trunc_uri($this->uri);

        $this->path = $uri['path'];
        $this->query = $uri['query'];
        $this->segments = $uri['segments'];

        $this->routes = Router::routes();

        $this->register = new RouteRegister();
        $this->register->boot();
        $this->exec();
    }

    private function exec(): void
    {
        $register = $this->load($this->path, $this->register);
        require_once("../routes/{$register['type']}.php");
        $this->route_path = $register['route'];

        $routing = $this->routing($register['register_count']);
        if (!$routing) {
            pre_print('404 Not Found');
            exit();
        }

        $this->route = new Route(
                $routing['controller'],
                $routing['method'],
                $this->path,
                $this->query,
                $this->route_path,
                $this->segments,
                $routing['call_input'],
                $register['type'],
                $register['register'],
                $register['register_count']
        );
//        pre_print($register);
//        pre_print($this->route);
//        pre_print([$register, $this->path]);
//        pre_print($routing);
    }

    private function get_request_uri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function middlewares()
    {
        return isset($this->route_info['middlewares']) ? $this->route_info['middlewares'] : [];
    }

    private function check_middlewares()
    {
        $middlewares_list = $this->middlewares();
        if (!$middlewares_list) {
            return;
        }

        $midd_obj = new \Core\MiddlewareHandler($middlewares_list);
        $midd_obj->start($this->route_info['call_input']);
    }

    private function routing(int $register_count): ?array
    {
        $controller = '';
        $method = '';
        foreach (Router::routes() as $pattern => $r) {
            $call_input = [];
            $pattern = Tools::trunc_route($pattern);
            if (preg_match('/{[a-zA-Z]+:(any|num|num_gtz|entity|model|repository)}/', $pattern)) {
                $pattern_exploded = explode('/', $pattern);
                $i = 0;
                $binds = array_keys($this->register->bind_segments);
                foreach ($pattern_exploded as $segment) {
                    if (preg_match('/^{[a-zA-Z]+:(' . implode('|', $binds) . ')}$/', $segment) && isset($this->segments[$i])) {
                        $segment_exploded = explode(':', str_replace(['{', '}'], '', $segment));
                        $name = $segment_exploded[0];
                        $type = $segment_exploded[1];
                        $holder = $this->register->bind_segments[$type];
                        $pattern = str_replace($segment, $holder, $pattern);
                        if (isset($this->segments[$i + $register_count])) {
                            $call_input[$name] = ['value' => $this->segments[$i + $register_count], 'pattern' => $holder, 'type' => $type, 'index' => $i + $register_count];
                        }
                    }
                    $i++;
                }
            }

            if (preg_match('/^' . str_replace('/', '\/', $pattern) . '$/', $this->route_path)) {
                $controller = $r['actions'][0];
                $method = $r['actions'][1];

                break;
            }
        }

        if (!$controller) {
            return null;
        }
        return ['controller' => $controller, 'method' => $method, 'call_input' => $call_input];
    }

    public function load(string $path, RouteRegister $route_register): array
    {
        $type = 'web';
        $register = '/';
        $route = $path;

        if ($path == '/') {
            return ['type' => $type, 'register' => $register, 'route' => $route, 'register_count' => 0];
        }

        $url_segments = explode('/', $path);

        $segment = '';
        $i = 0;
        foreach ($url_segments as $s) {
            if ($i == 0) {
                $segment = $s;
            } else {
                $segment .= "/" . $s;
            }

            if (array_key_exists($segment, $route_register->routes)) {
                $type = $route_register->routes[$segment];
                $register = $segment;
                $route = implode('/', array_slice($url_segments, $i + 1));
                break;
            }
            $i++;
        }
        if ($route == '') {
            $route = '/';
        }
        return ['type' => $type, 'register' => $register, 'route' => $route, 'register_count' => count(explode('/', $register))];
    }

    function run()
    {
        if (!$this->route->valid_method()) {
            pre_print("Route {$this->route->get_namespace()} does not {$this->route->get_method()}");
        }

        $this->route->action_method();
    }
}
