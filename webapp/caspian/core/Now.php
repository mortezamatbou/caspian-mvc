<?php

class Now {
    
    static $route = '/';
    
    
    /**
     * Name of current Controller
     * @var string
     */
    static $controller = NULL;
    
    /**
     * Name of action of controller. actually this is name of controller methods that was run
     * @var string
     */
    static $action = NULL;
    
    /**
     * Local Route if routing from /application/controllers
     * Global Route if routing from /application/config/routes.php
     * @var string
     */
    static $controller_type = NULL;
    
    /**
     * Controller file name. for ./vendor/application/controllers/Welcome.php $fileName is Welcome
     * @var string
     */
    static $file_name = NULL;
    
    /**
     * Controller full path. example : ./vendor/application/controllers/Welcome.php
     * @var string
     */
    static $controller_path = NULL;
    
    /**
     * if controller detected from /application/controllers directory it is FALSE
     * and if detected from /application/config/routes.php it is TRUE
     * @var boolean
     */
    static $is_global_route = FALSE;
    
    /**
     * Define module name for current controller
     * @var string 
     */
    static $module_name = NULL;
    /**
     * Define module path for current controller
     * @var string 
     */
    static $module_path = NULL;
    
    /**
     * Define this controller is module or not
     * @var boolean 
     */
    static $has_module = FALSE;
    
    
    static function get_controller() {
        return self::$controller . '/' . self::$action;
    }
    
    static function get_route() {
        return self::$route;
    }
}
