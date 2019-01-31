<?php

class View  {
    
    protected $view;
    
    /**
     * given a file name of a View and render it
     * 
     * @param string $view_name file name without .php extension . file name can to contain a subdirectory path with file name
     * @param array $params give a array for passed values to a View file
     */
    public function render($view_name, $params = array()) {
        
        if(is_array($params) && !empty($params)) {
            foreach ($params as $k => $v) {
                ${$k} = $v;
            }
        }
        
        // a view can be .php
        include VIEWS_PATH . DIRECTORY_SEPARATOR . $view_name . '.php';
    }
    
    /**
     * given a file name of a View and render it
     * 
     * @static
     * @param string $view_name return content of a View file directly
     * @param array $params give a array for passed values to a View file
     */
    public static function show($view_name, $params = array()) {
        if(is_array($params) && !empty($params)) {
            foreach ($params as $k => $v) {
                ${$k} = $v;
            }
        }
        
        // a view can be .php extension
        include(VIEWS_PATH . DIRECTORY_SEPARATOR . $view_name . '.php');
    }
    
}

