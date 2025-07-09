<?php

namespace App\Validation;

use Core\Validation;

class PartnershipFormValidation extends Validation {

    function __construct($mode = 'API', $method = 'GET')
    {
        parent::__construct($mode, $method);
    }

    public function make(): bool
    {

        return TRUE;
    }

    public function data()
    {
        parent::get_data();
    }
}
