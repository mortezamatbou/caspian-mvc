<?php

namespace Caspian\Core;

class Security {

    function __construct() {
        
    }

    /**
     * this method check a input and return its result
     * @param string $value
     * @param int $type
     */
    function filter_value($action, $name, $type = FILTER_SANITIZE_STRIPPED) {
        $result = NULL;
        if (empty($name) && empty($action)) {
            return $result;
        }
        switch ($action) {
            case 'GET':
                $result = filter_input(INPUT_GET, $name, $type);
                break;
            case 'POST':
                $result = filter_input(INPUT_POST, $name, $type);
                break;
            case 'SESSION':
                $result = filter_input(INPUT_SESSION, $name, $type);
                break;
            case 'COOKIE':
                $result = filter_input(INPUT_COOKIE, $name, $type);
                break;
            case 'SERVER':
                $result = filter_input(INPUT_SERVER, $name, $type);
                break;
        }
        return $result;
    }

    function xss_clean($value) {
        if (is_array($value)) {
            $temp = array();
            foreach ($value as $k => $v) {
                $k_strip = strip_tags($k);
                $v_strip = strip_tags($v);

                $temp[htmlentities($k_strip)] = htmlentities($v_strip);
            }
            return $temp;
        }
        if ($value) {
            $result = strip_tags($value);
            if (isset($result) && !empty($result)) {
                return htmlentities($result);
            }
        }
        return NULL;
    }
    
    function escape_html($value) {
        if (is_array($value)) {
            $temp = array();
            foreach ($value as $k => $v) {
                $temp[htmlentities($k)] = htmlentities($v);
            }
            return $temp;
        }
        if ($value) {
            $result = $value;
            if (isset($result) && !empty($result)) {
                return htmlentities($result);
            }
        }
        return NULL;
    }

}
