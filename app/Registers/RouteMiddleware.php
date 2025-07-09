<?php

namespace App\Register;

class Routes {

    private array $routes = [];

    public function boot(): void
    {
        $this->routes['/'] = 'web';
        $this->routes['api'] = 'api';
        $this->routes['home'] = 'blog';
    }

    public function load(string $path): string
    {
        pre_print($path);
        return $this->routes;
    }

    public function registers(): array
    {
        return $this->registers;
    }
}
