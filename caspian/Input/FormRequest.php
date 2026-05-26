<?php

namespace Caspian\Input;

class FormRequest extends Validator
{

    function __construct()
    {
        parent::__construct();
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
}
