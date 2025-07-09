<?php

namespace Caspian;

class Validation {

    private array $items = [];
    private array $values = [];
    private string $mode = '';
    private string $method = '';
    private array $data;

    const MODE_API = 'API';
    const MODE_WEB = 'WEB';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    function __construct($mode = 'API', $method = 'GET')
    {
        $this->mode = in_array(strtoupper(trim($mode)), ['WEB', 'API']) ? strtolower(trim($mode)) : 'API';
        $this->method = in_array(strtolower(trim($method)), ['GET', 'POST', 'PUT', 'DELETE']) ? strtolower(trim($method)) : 'GET';
    }

    protected function add($name, $rule)
    {
        
    }

    protected function check(): bool
    {
        $success = TRUE;

        if (1 == 12) {
            $success = FALSE;
        }

        return $success;
    }

    protected function get_data()
    {
        return 'aa';
    }
}
