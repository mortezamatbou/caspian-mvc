<?php

/**
 * 
 */
class Session {
    
    private $security;

    /**
     * when load_session() called in a controller, it's means that we want to use session
     * so in default, in Session constructor session_start called
     */
    function __construct() {
        session_start();
        $this->security = new Security();
    }

    /**
     * destroy all session in global $_SESSION variable
     */
    function destroy_all() {
        session_destroy();
    }

    /**
     * unset a item or items (in array) from $_SESSION global variable
     * @param string $name session name
     */
    function unset_item($name = '') {
        if (is_array($name) && !empty($name)) {
            foreach ($name as $key) {
                $k = $this->security->xss_clean($key);
                if (!empty($k)) {
                    unset($_SESSION[$k]);
                }
            }
        } else if ($name) {
            $n = $this->security->xss_clean($name);
            unset($_SESSION[$n]);
        }
    }

    /**
     * this method set a session by name and its value
     * @param string $name session name
     * @param string $val session value, it can array or string
     */
    function set_item($name = '', $val = '') {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $k = $this->security->xss_clean($key);
                $v = $this->security->xss_clean($value);
                if (!empty($k) && !empty($v)) {
                    $_SESSION[$k] = $v;
                }
            }
            return TRUE;
        } else if ($name && $val) {
            $k = $this->security->xss_clean($name);
            $v = $this->security->xss_clean($val);
            if (!empty($k) && !empty($v)) {
                $_SESSION[$k] = $v;
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * $name can be null, so return all sessions in a array, otherwise return value of an item
     * @param string $name
     * @return array
     */
    function item($name = '') {
        if ($name) {
            $n = $this->security->xss_clean($name);
            if (isset($_SESSION[$n]) && !empty($_SESSION[$n])) {
                return $_SESSION[$n];
            }
            return NULL;
        }
        return $_SESSION;
    }

    /**
     * 
     * @param string $item
     * @return boolean
     */
    function has_item($item = '') {
        if ($item) {
            $i = $this->security->xss_clean($item);
            if (isset($_SESSION[$i])) {
                return TRUE;
            }
            return FALSE;
        }
        return FALSE;
    }

}

/**
 * receive a array that is contain session information
 * @param array $data
 *
 * 
function set_data_AA($data) {
    
      check sessions key and value for bad entry and assign it
     
    if (is_array($data) && !empty($data)) {
        foreach ($data as $key => $value) {
            $k = $this->security->xss_clean($key);
            $v = $this->security->xss_clean($value);
            if (!empty($k) && !empty($v)) {
                $_SESSION[$k] = $v;
            }
        }
    }
}
*/
