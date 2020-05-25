<?php

namespace Caspian\Core;

/**
 * this class include common methods that is could essential or not
 * for example include a helper file to framework for use
 */
class Load {
    
    /**
     * include a Helper file in framework with Helper name or an helper name that contain a path with name
     * @param string $helper_name
     */
    public function helper($helper_name) {
        include_once HELPERS_PATH . '/' . $helper_name . '.helper.php';
    }
    public function helper_($helper_name) {
        include_once HELPERS_PATH_ . '/' . $helper_name . '.helper.php';
    }

    /**
     * include a Model file in framework with Model name or an model name that contain a path with name
     * @param string $model_name
     */
    public function model($model_name, $obj = false, $param = NULL) {
        // use singleton method &load_class for loading
        include_once MODELS_PATH . '/' . $model_name . '.php';
        if ($obj) {
            $class_name = 'Caspian\\Core\\Database\\' . $model_name;
            if ($param) {
                return new $class_name($param);
            }
            return new $class_name();
        }
    }
    public function model_($model_name, $obj = false, $param = NULL) {
        // use singleton method &load_class for loading
        include_once MODELS_PATH_ . '/' . $model_name . '.php';
        if ($obj) {
            if ($param) {
                return new $model_name($param);
            }
            return new $model_name();
        }
    }

    /**
     * include a Library file in framework with Library name or an library name that contain a path with name
     * @param string $library_name
     */
    public function library($library_name, $param = '') {
        // use singleton method &load_class for loading
        return load_class($library_name, $param, FALSE);
    }
    public function library_($library_name, $param = '') {
        return load_class($library_name, $param, TRUE);
    }


}
