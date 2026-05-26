<?php

namespace App\Requests;

use Caspian\Input\FormRequest;
use Caspian\Input\ValidatorInterface;

class LoginRequest extends FormRequest implements ValidatorInterface
{

    function __construct()
    {
        parent::__construct();
    }

    function rules(): ?array
    {
        return [
            ''
        ];
    }

    public function route_mode(): bool
    {
        return TRUE;
    }
}
