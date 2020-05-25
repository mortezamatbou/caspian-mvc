<?php

class View {

    protected $view;

    /**
     * given a file name of a View and render it
     * 
     * @param string $view_name file name without .php extension . file name can to contain a subdirectory path with file name
     * @param array $params give a array for passed values to a View file
     */
    public function render($view_name, $params = array()) {
        global $config;
        $template = isset($config['template']) && $config['template'] ? $config['template'] . '/' : '';
        if (is_array($params) && !empty($params)) {
            foreach ($params as $k => $v) {
                ${$k} = $v;
            }
        }

        // a view can be .php
        include VIEWS_PATH . DIRECTORY_SEPARATOR . $template . $view_name . '.php';
    }

    /**
     * by this method we can load view of another modules.
     * if module be null, then view path is define by primary module of framework.
     * if defined module has $config['template'] value in config.php in config folder this will set automatically
     * 
     * @param string $view_name file name without .php extension . file name can to contain a subdirectory path with file name
     * @param array $params give a array for passed values to a View file
     * @param string $module Module name. this must be name of module in /application/modules. if valid name, then load defined view
     */
    public function render_module($view_name, $params = array(), $module = '') {

        $module_path = '';

        if ($module) {
            $module_path = MODULE_DIR . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR;

            if (!is_dir($module_path)) {
                return;
            }
            
            
        } else {
            // primary module of site
            $module_path = APPPATH_;
        }

        if (is_array($params) && !empty($params)) {
            foreach ($params as $k => $v) {
                ${$k} = $v;
            }
        }
        
        // include config.php from module
        
        include $module_path . 'config' . DIRECTORY_SEPARATOR . 'config.php';
        
        $template = isset($config['template']) && $config['template'] 
                ? 'views/' . $config['template'] . '/' 
                : 'views/';
        
        
        // a view can be .php
        include $module_path . $template . $view_name . '.php';
    }

    /**
     * given a file name of a View and render it
     * 
     * @static
     * @param string $view_name return content of a View file directly
     * @param array $params give a array for passed values to a View file
     */
    public static function show($view_name, $params = array()) {
        global $config;
        $template = isset($config['template']) && $config['template'] ? $config['template'] . '/' : '';
        if (is_array($params) && !empty($params)) {
            foreach ($params as $k => $v) {
                ${$k} = $v;
            }
        }

        // a view can be .php extension
        include(VIEWS_PATH . DIRECTORY_SEPARATOR . $template . $view_name . '.php');
    }

}
