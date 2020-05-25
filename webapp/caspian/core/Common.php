<?php

if (!function_exists('autoload_files')) {

    function autoload_files() {
        global $autoload;
        if ($autoload) {
            // load the files
            $instance = get_instance();
            $libs = $autoload['libraries'];
            if ($libs) {
                foreach ($libs as $lib) {
                    if ($lib) {
                        $instance->load->library($lib);
                    }
                }
            }
            $models = $autoload['model'];
            if ($libs) {
                foreach ($models as $model) {
                    if ($model) {
                        $instance->load->model($model);
                    }
                }
            }
            $helpers = $autoload['helper'];
            if ($libs) {
                foreach ($helpers as $helper) {
                    if ($helper) {
                        $instance->load->helper($helper);
                    }
                }
            }
        }
    }

}


/** Loader functions * * ** * * ** * * * ** * * * ** * * ** * * ** * * * ** * * * ** */
if (!function_exists('load_class')) {
    /** This method exactly copied from Codeigniter framework */

    /**
     * Class registry
     *
     * This function acts as a singleton. If the requested class does not
     * exist it is instantiated and set to a static variable. If it has
     * previously been instantiated the variable is returned.
     *
     * @param	string	the class name being requested
     * @param	string	the directory where the class should be found
     * @param	mixed	an optional argument to pass to the class constructor
     * @return	object
     */
    function &load_class($class, $param, $main = FALSE) {
        static $_classes = array();

        // Does the class exist? If so, we're done...
        if (isset($_classes[$class])) {
            return $_classes[$class];
        }

        $name = FALSE;

        
        $search = !$main ? array(CASPIAN_LIBRARIES_PATH, LIBRARIES_PATH) : array(CASPIAN_LIBRARIES_PATH, LIBRARIES_PATH_);
        // Look for the class first in the local application/libraries folder
        // then in the native caspian/core folder
        foreach ($search as $path) {
            if (file_exists($path . '/' . $class . '.php')) {
                $name = $class;

                if (class_exists($name, FALSE) === FALSE) {
                    require_once($path . '/' . $class . '.php');
                }

                break;
            }
        }

        // Is the request a class extension? If so we load it too
        if (file_exists(LIBRARIES_PATH . '/' . $class . '.php')) {
            $name = $class;

            if (class_exists($name, FALSE) === FALSE) {
                require_once(LIBRARIES_PATH . '/' . $name . '.php');
            }
        }

        // Did we find the class?
        if ($name === FALSE) {
            // Note: We use exit() rather than show_error() in order to avoid a
            // self-referencing loop with the Exceptions class
            /*             * * ******** set_status_header(503); * */
            echo 'Unable to locate the specified class: ' . $class . '.php';
            exit(5); // EXIT_UNK_CLASS
        }

        // Keep track of what we just loaded
        is_loaded($class);

        $_classes[$class] = isset($param) ? new $name($param) : new $name();
        return $_classes[$class];
    }

}

if (!function_exists('is_loaded')) {
    /** This method exactly copied from Codeigniter framework */

    /**
     * Keeps track of which libraries have been loaded. This function is
     * called by the load_class() function above
     *
     * @param	string
     * @return	array
     */
    function &is_loaded($class = '') {
        static $_is_loaded = array();

        if ($class !== '') {
            $_is_loaded[strtolower($class)] = $class;
        }

        return $_is_loaded;
    }

}


/** Hook functions * * ** * * ** * * * ** * * * ** * * ** * * ** * * * ** * * * ** */
if (!function_exists('hooking')) {

    function hooking($hook_point) {
        global $__hook;
        if ($__hook->enable) {
            $__hook->hook_event($hook_point);
        }
    }

}

if (!function_exists('trigger')) {

    function trigger($trigger_point) {
        if (get_instance()->plugin->enable) {
            get_instance()->plugin->trigger($trigger_point);
        }
    }

}

/**
 * 
 * @param string $input entry route in url
 * @param array $global_route
 * @return mixed an route or null
 */
function check_global_route($input, $global_route) {
    if (is_array($global_route)) {
        foreach ($global_route as $pattern => $route) {
            $p = '/^' . $pattern . '$/i';
            if (preg_match($p, $input)) {
                Now::$is_global_route = TRUE;
                return $route;
            }
        }
    }
    Now::$is_global_route = FALSE;
    return NULL;
}

/**
 * This method check method parameters if required, send parameters to method by next segments
 * @param object $class_obj object of current class that we want action this method
 * @param string $method_name name of method name that we want to execute
 * @param int $current_segment_num number of current segment [this number is method index in $segments array]
 * @param array $segments this is a array that contain url segments
 */
function action_method($class_obj, $method_name, $current_segment_num, $segments) {
    $methods = new ReflectionMethod($class_obj, $method_name);
    $params = array();
    $number_of_params = $methods->getNumberOfParameters();
    if ($number_of_params) {
        // fetch next segments after current segments [method segment]
        for ($i = $current_segment_num + 1; $i < count($segments); $i++) {
            $params[] = $segments[$i];
        }
    }

    call_user_func_array(array($class_obj, $method_name), $params);
}

/** -------------------------------------------------------------------------------------------------- */

function clear_url(&$url) {
    $count = count($url);
    for ($i = 0; $i < $count; $i++) {
        if ($url[$i] == '') {
            unset($url[$i]);
            $count = count($url);
        }
    }
}

/**
 * this method return base_url value in $config global variable
 * @global array $config
 * @return string
 */
function base_url() {
    global $config;
    echo $config['base_url'];
}

function get_current_page_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://';
    return $protocol . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
}

function get_base_url() {
    global $config;
    return $config['base_url'];
}


function base_upload_directory() {
    global $config;
    echo $config['base_upload_directory'];
}

function show_404($error_message = NULL) {
    global $route;
    ob_clean();
    header_remove();
    http_response_code(404);
    View::show('errors/' . $route['404_override'], ['message' => $error_message]);
    exit();
}

function is_https() {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
}