<?php

namespace App\Middleware;

use App\Library\CaptchaCheckerLibrary;
use Core\MiddlewareInterface;

class TestMiddleware implements MiddlewareInterface {

    public function exec(array $previous_response = [], array $params = []): array
    {
        $status = FALSE;

        $input = file_get_contents('php://input');
        $captcha = $input ? json_decode($input, TRUE) : [];

        $uuid = isset($captcha['captcha_key']) && $captcha['captcha_key'] ? strip_tags(trim($captcha['captcha_key'])) : '';
        $code = isset($captcha['captcha_value']) && $captcha['captcha_value'] ? strip_tags(trim($captcha['captcha_value'])) : '';
        $section = isset($captcha['captcha_section']) && $captcha['captcha_section'] ? strip_tags(trim($captcha['captcha_section'])) : '';

        if ($captcha || !$uuid || !$code || !$section) {
            $captchaLib = new CaptchaCheckerLibrary($uuid, $code, $section);
            $status = $captchaLib->check_code();
        }

        return ['success' => $status, 'data' => [], 'stop_on_fail' => TRUE];
    }

    public function get_error(): array
    {
        return [];
    }

    public function get_error_api(): array
    {
        return [
            'status' => 400,
            'body' => [],
            'error' => 'Invalid captcha code'
        ];
    }
}
