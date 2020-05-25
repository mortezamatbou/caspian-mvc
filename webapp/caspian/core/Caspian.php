<?php

/**
 * Reference to the Controller method.
 * Returns current CASPIAN instance object
 *
 * @return Controller
 */
function &get_instance() {
    return Controller::get_instance();
}

class Caspian {

    public $lock = FALSE;

    /**
     * count of url segment
     * @var int 
     */
    public $count;

    /**
     * in default , if entry url invalid this controller called
     * @var array 
     */
    public $url;

    /**
     * global $route array locate in application/config/routes.php file
     * @var array
     */
    public $route;
    
    /**
     * an array for current route middlewares
     * @var array
     */
    public $middlewares;

    /**
     * global $config array locate in application/config/config.php file
     * @var array 
     */
    public $config;

    /**
     *
     * @var string
     */
    public $temp_url = NULL;

    function __construct() {

        $input = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRIPPED);

//        global $route;
        global $config;

        $this->route = Route::routes();
        $this->config = $config;

        if ($input && preg_match('/\/$/', $input)) {
            $input = substr($input, 0, strlen($input) - 1);
        }


        if (MODULE_NAME) {
            Now::$has_module = TRUE;
            Now::$module_name = MODULE_NAME;
            Now::$module_path = MODULE_PATH;

            if (count(explode('/', $input)) == 1) {
                Now::$controller_type = 'Global Route';
                $this->url = explode('/', $this->route['welcome_page']['actions']);
                $this->middlewares = $this->route['welcome_page']['middleware'];
            } else {
                $url_witout_module = $this->remove_module_from_url($input);
                $is_global_route = check_global_route($url_witout_module, $this->route);
                if ($is_global_route) {
                    $this->url = explode('/', $is_global_route['actions']);
                    $this->url = $is_global_route ? explode('/', $is_global_route['actions']) : explode('/', $url_witout_module);
                    $this->middlewares = $is_global_route['middleware'];
                } else {
                    $this->url = explode('/', $url_witout_module);
                }
            }
        } else if (!$input) {
            $this->url = explode('/', $this->route['welcome_page']['actions']);
            $this->middlewares = $this->route['welcome_page']['middleware'];
        } else {
            $is_global_route = check_global_route($input, $this->route);
            if ($is_global_route) {
                $this->url = explode('/', $is_global_route['actions']);
                $this->middlewares = $is_global_route['middleware'];
            } else {
                $this->url = explode('/', $input);
            }
        }

        if (preg_match("/{$config['permitted_uri_chars']}/i", $input)) {
            show_404("your url has invalid character. not clean {$input}");
        }

        if (count($this->url) > VALID_SEGMENT_COUNT) {
            show_404("Your entry segment is more than valid count of url segment " . count($this->url));
        }

        $defender = new CaspianServiceDefender();

        $defender->run();

        $this->run();

        // post_system //
        hooking('post_system');
    }

    /**
     * check input url for detect its controller and run it
     */
    function run() {
        /**
         * $addr variable define controller path of controller files
         */
        $addr = CONTROLLERS_PATH;

        /**
         * check every segment that is directory or a controller file
         */
        for ($i = 0; $i < count($this->url); $i++) {
            /**
             * append first segment to $addr variable and check it for directory or file
             */
            $addr .= '/' . $this->url[$i];
            $next_segment = ($i + 1) < count($this->url) ? ($i + 1) : 0;

            /**
             * check segments for directory or file
             * and search for methods or ... 
             */
            // directory dar web service framework ha check nemishavand
            if (is_dir($addr)) {
                // check for [next segment] [exist] or [not]
                // if not
                if (!$next_segment) {
                    show_404("Error[9882346]: for [{$this->url[$i]}] DIRECTORY NOT EXISTS");
                }
            } else {
                /**
                 * if current segment is not a directory, check it for a file
                 */
                if (file_exists($addr . '.php')) {
                    require $addr . '.php';
                    Now::$file_name = $this->url[$i] . '.php';
                    Now::$controller_path = $addr . '.php';
                    /**
                     * if next segment is [not exist]
                     */
                    if (!$next_segment) {
                        # search for index else search for method by next segment name
                        // pre_controller //
                        hooking('pre_controller');
                        if (class_exists($this->url[$i])) {
                            Now::$controller = $this->url[$i];
                            $obj = new $this->url[$i];
                            if (method_exists($obj, 'index')) {
                                Now::$action = 'index';
                                // post_constructor_controller //
                                hooking('post_constructor_controller');

                                // run middlewares of this Controller
                                $this->check_middlewares();
                                
                                $obj->index();

                                // post_controller //
                                hooking('post_controller');
                                break;
                            }
                            show_404("Error[336143]: METHOD [INDEX] NOT EXISTS IN [{$addr}.php]");
                        }
                        show_404("Error[4463792]: [{$this->url[$i]}] CLASS NOT EXISTS [{$addr}.php]");
                    }

                    if (class_exists($this->url[$i])) {
                        Now::$controller = $this->url[$i];
                        // pre_controller //
                        hooking('pre_controller');
                        $obj = new $this->url[$i];
                        /**
                         * IF: for a segment, next segment is exist , check it for method this file
                         * ELSE: throw a Not Found error
                         */
                        if (method_exists($obj, $this->url[$i + 1])) {

                            Now::$action = $this->url[$i + 1];
                            // post_constructor_controller //
                            hooking('post_constructor_controller');

                            // run middlewares of this Controller
                            $this->check_middlewares();
                            
                            action_method($obj, $this->url[$i + 1], $i + 1, $this->url);
                            // post_controller //
                            hooking('post_controller');
                            break;
                        }
                        show_404("Error[221437]: [{$this->url[$i + 1]}] METHOD NOT EXISTS IN [{$this->url[$i]}] CLASS");
                    }
                    show_404("Error[5563789]: [{$this->url[$i]}] CLASS NOT EXISTS [{$addr}.php]");
                }
                show_404("Error[119735]: [{$this->url[$i]}] FILE NOT EXISTS [{$addr}.php]");
            }
        }
    }

    function remove_module_from_url($input) {
        if ($input) {
            return str_replace(Now::$module_name . '/', '', $input);
        } else {
            return FALSE;
        }
    }
    
    private function check_middlewares() {
        
        if (!$this->middlewares) {
            return;
        }
        
        $middleware = load_class('Middleware', $this->middlewares);
        
        $middleware->exec();
        
    }

}
