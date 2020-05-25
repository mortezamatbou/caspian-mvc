<?php

namespace Caspian\Core\Http;

class Input {

    /**
     *
     * @var object an instance of Security class
     */
    private $security;

    function __construct() {
        $this->security = load_class('Security', '', 'Caspian\\Core\\');
    }

    /**
     * this method give a name of value that we want invoked in url for GET request
     * 
     * $clean item in default is 1 and it can be 0 and 3
     * 
     * if $clean be 0 this method return direct input in url and our target item value
     * if $clean be 1 this use strip_tags method and ignore all tags
     * if $clean be 2 this use add slashes method and use \ before double 
     * 
     * @param string $item
     * 
     * @return mixed return a string if value valid or NULL
     */
    public function get($item, $filter = FILTER_SANITIZE_STRIPPED) {
        if (empty($item)) {
            return NULL;
        }
        $input = $this->security->filter_value('GET', $item, $filter);
        return $input;
    }

    /**
     * this method give a name of value that we want invoked in url for POST request
     * 
     * $clean item in default is 1 and it can be 0 and 3
     * 
     * if $clean be 0 this method return direct input in url and our target item value
     * if $clean be 1 this use strip_tags method and ignore all tags
     * if $clean be 2 this use add slashes method and use \ before double 
     * 
     * @param string $item
     * @param int $clean
     * 
     * @return mixed return a string if value valid or NULL
     */
    public function post($item, $filter = FILTER_SANITIZE_STRIPPED) {
        if (empty($item)) {
            return NULL;
        }
        $input = $this->security->filter_value('POST', $item, $filter);
        return $input;
    }

    /**
     * this method will return ip address for entry request
     * @return string
     */
    public function ip_address() {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * return a segment of url if by $segment_number an value exists otherwise return NULL
     * this method is used when we give a route matched by global_route variable, in this case maybe user want to give segments
     * after pattern as input data for manipulating to database. for example assuming the following example
     * 
     * http://site.ir/blog/category/php
     * 
     * we have a global route as: $route['blog/category/[a-z]']
     * in this case when invoke [a-z] segments, we should use segment method with this index, index of url segment start in 0,
     * and the first segment is the first value in url, that's mean
     * 
     * segment(1) => blog
     * segment(2) => category
     * segment(3) => php
     * 
     * @param int $segment_number index of url segment you want invoke
     * @return string 
     */
    public function segment($segment_number, $safe = FALSE) {
        /**
         * get entry url and explode it
         */
        $segments = explode('/', $this->get('url'));
        $count = count($segments);
        /**
         * if any segment not exists return null
         */
        if ($count == 0) {
            return NULL;
        }
        /**
         * ignore all empty value of url
         */
        for ($i = 0; $i < $count; $i++) {
            if ($segments[$i] == '') {
                unset($segments[$i]);
                $count = count($segments);
            }
        }
        /**
         * if all items are correct return invoked value
         */
        if ($segment_number > 0 && is_array($segments) && $segment_number <= $count) {
            $segment = $safe ? strip_tags($segments[$segment_number-1]) : $segments[$segment_number-1];
            return $segment;
        }
        return NULL;
    }
    
    
    // Server
}
