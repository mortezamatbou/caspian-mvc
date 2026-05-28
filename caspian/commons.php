<?php

use Caspian\Core\Registry;
use Caspian\Core\Route;
use Caspian\Core\Validator;
use Caspian\Core\ValidatorInterface;

function send_response_json(array $response, $status = 200, $extra = [])
{
    header('Content-type: application/json');
    echo json_encode(['status' => $status, 'body' => $response, 'extra' => $extra]);
    exit;
}

function route(): Route
{
    return Registry::get('loader')->route();
}

function validate(ValidatorInterface $validator): Validator
{
    return new Validator($validator);
}


if (!function_exists('ci_remove_invisible_characters')) {
    /**
     * Remove Invisible Characters
     *
     * This prevents sandwiching null characters
     * between ascii characters, like Java\0script.
     *
     * @param	string
     * @param	bool
     * @return	string
     */
    function ci_remove_invisible_characters($str, $url_encoded = TRUE)
    {
        $non_displayables = array();

        // every control character except newline (dec 10),
        // carriage return (dec 13) and horizontal tab (dec 09)
        if ($url_encoded) {
            $non_displayables[] = '/%0[0-8bcef]/i';    // url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/i';    // url encoded 16-31
            $non_displayables[] = '/%7f/i';    // url encoded 127
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';    // 00-08, 11, 12, 14-31, 127

        do {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);

        return $str;
    }
}
