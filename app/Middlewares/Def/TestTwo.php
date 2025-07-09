<?php

namespace App\Middleware\Def;

use Core\MiddlewareInterface;

class TestTwo implements MiddlewareInterface {

    public function exec(array $previous_response = [], array $params = []): array
    {
        pre_print(array_merge($previous_response, $params), 'json');
    }

    public function get_error(): array
    {
        
    }
}
