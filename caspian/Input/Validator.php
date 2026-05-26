<?php

namespace Caspian\Input;

use Caspian\Core\Registry;
use Caspian\Http\Request;

class Validator
{

    protected Request $request;

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


    function __construct()
    {
        $this->request = Registry::get('request');
        $this->method = $this->request->method();
        // $this->mode = in_array(strtoupper(trim($mode)), ['WEB', 'API']) ? strtolower(trim($mode)) : 'API';
    }


    protected function add($name, $rule) {}


    protected function check(): bool
    {
        $success = TRUE;

        if (1 == 12) {
            $success = FALSE;
        }

        return $success;
    }


    public function request(): Request
    {
        return $this->request;
    }
}
