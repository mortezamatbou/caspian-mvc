<?php

namespace Caspian\Core\Events;

class Hook {
    
    public $enable = 1;

    function __construct() {
        global $config;
        if (!$config['enable_hook']) {
            $this->enable = 0;
        }
    }

    /**
     * 
     * @global array $hook
     * @param string $event_name
     */
    function hook_event($hook_point) {
        global $hook;
        if (!isset($hook)) {
            return;
        }
        switch ($hook_point) {
            case 'pre_system':
                @$this->do_hook($hook['pre_system']);
                break;
            case 'pre_controller':
                @$this->do_hook($hook['pre_controller']);
                break;
            case 'post_constructor_controller':
                @$this->do_hook($hook['post_constructor_controller']);
                break;
            case 'post_controller':
                @$this->do_hook($hook['post_controller']);
                break;
            case 'post_system':
                @$this->do_hook($hook['post_system']);
                break;
            default :
                return 0;
        }
    }

    /**
     * for doing a hook 
     * @param array $hook_info
     */
    private function do_hook($hook_info) {

        if (is_callable($hook_info)) { // for closure hooks
            $hook_info();
            return;
        }

        if (is_array($hook_info)) {

            $type = $this->hook_type($hook_info);

            if ($type === 1) { // single
                $this->hook_action($hook_info);
                return;
            }
            if ($type === 2) { //multi
                $this->hook_multi_action($hook_info);
                return;
            }
        }
    }

    /**
     * 
     * @param array $hook
     * @return int
     */
    private function hook_type($hook) {
        if (is_array($hook)) {
            if (is_array($hook[0])) {
                return 2; // multi hook
            }
            if (empty($hook)) {
                return 0; // invalid hook
            }
            return 1; // single hook
        }
        return 0; // invalid hook
    }

    /**
     * 
     * @param type $hook_info
     */
    private function hook_action($hook_info) {
        // if hook is single
        $class = $hook_info['class'];
        $function = $hook_info['function'];
        $filename = $hook_info['filename'];
        $filepath = (isset($hook_info['filepath']) && !empty($hook_info['filepath'])) ? $hook_info['filepath'] : HOOKS_PATH;
        $params = (isset($hook_info['params']) && is_array($hook_info['params'])) ? $hook_info['params'] : 0;

        include_once $filepath . '/' . $filename;
        if ($params) {
            $hook_obj = new $class($params);
        } else {
            $hook_obj = new $class();
        }
        $hook_obj->$function();

        // End of if hook is single
    }

    /**
     * 
     * @param type $hook_info
     */
    private function hook_multi_action($hook_info) {
        for ($i = 0; $i < count($hook_info); $i++) {
            if (is_array($hook_info[$i])) {
                $this->hook_action($hook_info[$i]);
            } else if (is_callable($hook_info[$i])) {
                $hook_info[$i]();
            }
        }
    }

}
