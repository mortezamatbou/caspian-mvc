<?php

function pre_print($data, $pre = 'pre')
{
    if ($pre == 'pre') {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    } elseif ($pre == 'json') {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    } else {
        print_r($data);
    }
    exit;
}
