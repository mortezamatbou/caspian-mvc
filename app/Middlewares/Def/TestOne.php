<?php

namespace App\Middleware\Def;

use Core\MiddlewareInterface;

class TestOne implements MiddlewareInterface {

    public function exec(array $previous_response = [], array $params = []): array
    {
        $this->error_message = ['title' => 'Fucking Error'];
        return ['success' => TRUE, 'data' => ['name' => 'Morteza'], 'stop_on_fail' => TRUE];
    }

    public function get_error(): array
    {
        return $this->error_message;
    }
}
