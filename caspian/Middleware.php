<?php

namespace Caspian;

class Middleware {

    public array $middlewares, $middlewares_stack;
    private array $error_message;
    private array $call_input;
    private array $last_middl_response = ['success' => TRUE, 'data' => [], 'stop_on_fail' => TRUE];
    private $last_middl_obj = NULL;

    function __construct(array $middlewares)
    {
        $this->middlewares = $middlewares;
        $this->middlewares_stack = $middlewares;
    }

    function start($call_input)
    {

        $this->call_input = $call_input;
        if (!$this->middlewares) {
            return;
        }

        if (!is_array($this->middlewares) || !$this->middlewares_stack) {
            exit('Middleware must be define on array');
        }

        array_map([$this, 'iterate_stack'], $this->middlewares_stack);
    }

    private function iterate_stack()
    {
        if (!$this->middlewares_stack) {
            return;
        }

        $middleware_info = [
            'name' => array_key_first($this->middlewares_stack),
            'params' => $this->middlewares_stack[array_key_first($this->middlewares_stack)]
        ];
        $this->load_middleware($middleware_info);

        array_shift($this->middlewares_stack);
    }

    private function load_middleware($middleware_info)
    {
        $explode = explode('.', $middleware_info['name']);

        // namespace and folder_name is same
        $namespace = $explode[0] ?? '';
        $file_name = $explode[1] ?? '';

        if (!$namespace || !$file_name) {
            exit('middleware namespace or file name is invalid');
        }

        $file_path = '..' . APP_DIR_MIDDLEWARE . "{$namespace}/{$file_name}.php";
        if (!file_exists($file_path)) {
            exit(APP_DIR_MIDDLEWARE . "{$middleware_info}/$middleware_info}.php -> Not found");
        }

        // require_once $file_path;
        $class_name = "\\App\\Middleware\\{$namespace}\\{$file_name}";

        $obj = new $class_name();
        $this->last_middl_obj = $obj;

        $params = [];
        foreach ($middleware_info['params'] as $row) {
            $params[$row] = isset($this->call_input[$row]) ? $this->call_input[$row] : '';
        }

        $response = $obj->exec($this->last_middl_response['data'], $params);
        $this->last_middl_response = $response;

        $this->check_stop_on_fail();
    }

    private function check_stop_on_fail()
    {
        // if previous middleware is not successfull and it is need to stop on fail. show error message from $last_middl_obj (previously object)
        if (!$this->last_middl_response['success'] && $this->last_middl_response['stop_on_fail']) {
            if ($this->last_middl_obj) {
                $this->terminate_request();
            }
            exit('500 Internal Error');
        }
    }

    private function terminate_request()
    {
        header('Content-Type: application/json');
        echo json_encode($this->last_middl_obj->get_error_api());
        exit;
    }
}
