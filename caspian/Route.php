<?php

namespace Caspian;

class Route {

    private string $namespace;
    private string $method;
    private string $path;
    private string $query;
    private string $route;
    private string $type;
    private string $register;
    private int $register_count;
    private array $segments;
    private array $call_input;
    private Controller $controller;

    function __construct(string $namespace, string $method, string $path, string $query, string $route, array $segments, array $call_input, string $type, string $register, int $register_count)
    {
        $this->namespace = $namespace;
        $this->method = $method;
        $this->path = $path;
        $this->query = $query;
        $this->route = $route;
        $this->segments = $segments;
        $this->call_input = $call_input;
        $this->type = $type;
        $this->register = $register;
        $this->register_count = $register_count;

        $this->controller = new $namespace();
    }

    public function valid_method(): bool
    {
        if (!method_exists($this->controller, $this->method)) {
            return false;
        }
        return true;
    }

    public function action_method()
    {
        $reflection = new \ReflectionMethod($this->controller, $this->method);
        $params = [];

        foreach ($reflection->getParameters() as $param) {
            $input = $this->call_input[$param->getName()];
            if (!$param->hasType() || in_array($input['type'], ['any', 'num', 'num_gtz'])) {
                $params[] = $input['value'];
                continue;
            }
            $namespace = $param->getType()->getName();
            $params[] = new $namespace($input['value']);
        }

        // check middlewares
        // $this->check_middlewares();

        return call_user_func_array(array($this->controller, $this->method), $params);
    }

    public function get_namespace(): string
    {
        return $this->namespace;
    }

    public function get_method(): string
    {
        return $this->method;
    }

    public function get_path(): string
    {
        return $this->path;
    }

    public function get_query(): string
    {
        return $this->query;
    }

    public function get_route(): string
    {
        return $this->route;
    }

    public function get_segments(int $i = 0): string|array
    {
        if ($i) {
            return isset($this->segments[$i]) && $i >= 0 ? $this->segments[$i] : '';
        }
        return $this->segments;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_register(): string
    {
        return $this->register;
    }

    public function get_call_input(): array
    {
        return $this->call_input;
    }
}
