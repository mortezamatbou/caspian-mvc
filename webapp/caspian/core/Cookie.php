<?php

namespace Caspian\Core;

class Cookie {

    private $cookie;
    private $security;

    public function __construct() {
        global $config;
        $this->cookie = $config;
        $this->security = new Security();
    }

    /**
     * 
     * time() + 3600 define that cookie from now until 3600 seconds later (1 hour) will be expired
     * every 86400 = 1 day
     * every 3600 = 1 hour
     * 
     * if $clean be 0 this method return direct input in cookie
     * if $clean be 1 this use strip_tags method and ignore all tags
     * if $clean be 2 this use add slashes method and use \ before double " 
     * 
     * 
     * @param mixed $name it can be string or array (array use for set multi value)
     * @param array $opt
     * @param int $clean
     */
    public function set_cookie($name = '', $opt = array(), $clean = 1) {

        $option['cookie_prefix'] = (isset($opt['cookie_prefix']) && !empty($opt['cookie_prefix'])) ? $opt['cookie_prefix'] : $this->cookie['cookie_prefix'];
        $option['cookie_domain'] = (isset($opt['cookie_domain']) && !empty($opt['cookie_domain'])) ? $opt['cookie_domain'] : $this->cookie['cookie_domain'];
        $option['cookie_path'] = (isset($opt['cookie_path']) && !empty($opt['cookie_path'])) ? $opt['cookie_path'] : $this->cookie['cookie_path'];
        //$this->cookie['cookie_secure'] = (isset($opt['cookie_secure']) && !empty($opt['cookie_secure'])) ? $opt['cookie_secure'] : $this->cookie['cookie_secure'];
        $option['cookie_expire'] = (isset($opt['cookie_expire']) && !empty($opt['cookie_expire'])) ? $opt['cookie_expire'] : $this->cookie['cookie_expire'];

        switch ($clean) {
            case 0:
                if (is_array($name)) {
                    foreach ($name as $k => $cv) {
                        $n[$k] = $cv;
                    }
                }
                break;
            case 1:
                if (is_array($name)) {
                    foreach ($name as $k => $cv) {
                        $n[$this->security->xss_clean($k)] = $this->security->xss_clean($cv);
                    }
                }
                break;
            case 2:
                if (is_array($name)) {
                    foreach ($name as $k => $cv) {
                        $n[addslashes($k)] = addslashes($cv);
                    }
                }
                break;
            default :
                if (is_array($name)) {
                    foreach ($name as $k => $cv) {
                        $n[$this->security->xss_clean($k)] = $this->security->xss_clean($cv);
                    }
                }
                break;
        }

        if (is_array($n) && !empty($n)) {
            foreach ($name as $n => $v) {
                setcookie($option['cookie_prefix'] . $n, $v, time() + $option['cookie_expire'], $option['cookie_path']);
            }
            return 1;
        }
        return 0;
    }

    public function get($name, $prefix = '', $clean = 1) {
        switch ($clean) {
            case 0:
                break;
            case 1:
                $filter = FILTER_SANITIZE_STRIPPED;
                break;
            case 2:
                $filter = FILTER_SANITIZE_MAGIC_QUOTES;
                break;
        }
        if (empty($prefix)) {
            $prefix = $this->cookie['cookie_prefix'];
        }
        if (is_array($name) && !empty($name)) {
            $temp = array();
            foreach ($name as $n) {
                $pn = $prefix . $n;
                if ($clean == 0) {
                    $temp[$n] = $_COOKIE[$pn];
                } else {
                    $temp[$n] = $this->security->filter_value('COOKIE', $pn, $filter);
                }
            }
            return $temp;
        }
        if (!empty($name)) {
            $name = $prefix . $name;
            if ($clean == 0) {
                $temp = $_COOKIE[$name];
            } else {
                $temp = $this->security->filter_value('COOKIE', $name, $filter);
            }
            return $temp;
        }
        return 0;
    }

}
