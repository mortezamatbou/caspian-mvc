<?php

function redirect($location)
{
    $header = '';

    if (!preg_match('/^http/', $location) && !preg_match('/^\//', $location)) {

        $header = '/' . strip_input($location);
    } else {

        $header = strip_input($location);
    }

    header("location: $header");
    exit;
}

function send_response_json($response, $status = 200)
{
    header('Content-type: application/json');
    echo json_encode(['status' => $status, 'body' => $response]);
    exit;
}
