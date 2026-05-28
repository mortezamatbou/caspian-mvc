<?php

namespace Caspian\Core;

use Caspian\Core\Registry;
use Caspian\Http\Request;
use Exception;

class Validator
{

    private Request $request;
    public ValidatorInterface $interface;

    const VALIDATE_FORM = 'form';
    const VALIDATE_BODY = 'body';

    function __construct(ValidatorInterface $interface)
    {
        $this->request = Registry::get('request');
        $this->interface = $interface;
    }


    public function checkup(): void
    {
        // error
        // $this->interface->exception();
        // throw new Exception("Validation failed");

        // successful
        $this->interface->raw = [
            '1',
            '2',
            '3',
            '4'
        ];
        $this->interface->validated = [
            '1',
            '2',
            '3',
            '4'
        ];
    }

    public function auto()
    {
        return $this->interface->auto();
    }

    public function request(): Request
    {
        return $this->request;
    }
}
