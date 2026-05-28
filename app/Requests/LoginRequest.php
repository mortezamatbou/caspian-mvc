<?php

namespace App\Requests;

use Caspian\Core\Validation;
use Caspian\Core\Validator;
use Caspian\Core\ValidatorInterface;

class LoginRequest extends Validation implements ValidatorInterface
{

    public function rules(): array
    {
        return [
            'username' => 'string|min:5|max:10',
            'password' => 'string|min:6|max:10'
        ];
    }

    public function mode(): string
    {
        return Validator::VALIDATE_FORM;
    }

    public function auto(): bool
    {
        return TRUE;
    }

    public function exception(): void {}

    public function before(): void {}

    public function after(): void {}
}
