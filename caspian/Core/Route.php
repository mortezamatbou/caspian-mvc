<?php

namespace Caspian\Core;

use ReflectionClass;

class Route
{

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

            $input = isset($this->call_input[$param->getName()]) ? $this->call_input[$param->getName()] : NULL;

            if ($param->hasType() && !$input) {
                $params[] = new ($param->getType()->getName())();
                continue;
            }

            if (!$param->hasType() || in_array($input['type'], ['any', 'num', 'num_gtz'])) {
                $params[] = $input['value'];
                continue;
            }


            $params[] = new ($param->getType()->getName())($input['value']);

        }
        return call_user_func_array(array($this->controller, $this->method), $params);
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function query(): string
    {
        return $this->query;
    }

    public function get_route(): string
    {
        return $this->route;
    }

    public function segments(): array
    {
        return $this->segments;
    }

    public function segment(int $i): float|int|null|string
    {
        return isset($this->segments[$i]) ? $this->segments[$i] : '';
    }

    public function type(): string
    {
        return $this->type;
    }

    public function register(): string
    {
        return $this->register;
    }

    public function get_call_input(): array
    {
        return $this->call_input;
    }
}
