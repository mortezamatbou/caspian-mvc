<?php

namespace App\Registers;

class RouteRegister {

    public array $routes = [];
    public array $bind_segments = [
        'any' => '[a-zA-Z0-9_-]+',
        'num' => '[0-9]+',
        'num_gtz' => '[1-9][0-9]*',
        'entity' => '[a-zA-Z0-9_-]+',
        'model' => '[a-zA-Z0-9_-]+',
        'repository' => '[a-zA-Z0-9_-]+'
    ];

    public function boot(): void
    {
        $this->routes['/'] = 'web';
        $this->routes['api/v1'] = 'api';
        $this->routes['blog'] = 'blog';
    }

    public function registers(): array
    {
        return $this->registers;
    }
}
