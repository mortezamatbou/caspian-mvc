<?php

namespace Caspian\Core\Events;

class Middleware {

    public $middlewares;

    function __construct($middlewares) {
        $this->middlewares = $middlewares;
    }

    function exec() {

        if (!$this->middlewares) {
            return;
        }

        if (is_array($this->middlewares)) {
            foreach ($this->middlewares as $name) {
                $this->execute_by_name($name);
            }
        } else {
            $this->execute_by_name($this->middlewares);
        }
    }

    public function execute_by_name($name) {

        if (file_exists(MIDDLEWARE_DIR . "{$name}/{$name}.php")) {
            require_once MIDDLEWARE_DIR . "{$name}/{$name}.php";
        } else {
            exit('Not found middleware');
        }
    }

}
