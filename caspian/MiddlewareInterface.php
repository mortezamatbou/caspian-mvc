<?php

namespace Caspian;

interface MiddlewareInterface {

    public function exec(array $previous_response = [], array $params = []): array;

    public function get_error(): array;

    public function get_error_api(): array;
}
