<?php

class Plugin {

    var $enable = 1;

    function __construct() {
        global $config;
        if (!$config['enable_plugin']) {
            $this->enable = 0;
            return;
        } else {
            global $plugins;
            $this->view = get_instance()->view;
            $this->trigger_exec = new Plugin_exec($plugins);
        }
    }

    /**
     * | Body Trigger |
     * 
     * This method execute all methods in this Trigger Point
     * find all method and execute all of them 
     * 
     */
    function trigger($event_name) {
        if ($this->enable) {
            $this->trigger_exec->get_plugins_info($event_name);
        }
    }

    /**
     * | After Body Trigger |
     * 
     * This method execute all methods in this Trigger Point
     * find all method and execute all of them 
     * 
     */
}

# ******************************************************************************************************** #

class Plugin_exec {

    var $plugins;

    function __construct($plugins) {
        $this->plugins = $plugins;
    }

    /**
     * This method will return an array plugins info for defined event that this status is 1
     * 
     * @param string $event name of event trigger
     * @return array return all plugin that this status is 1
     */
    function get_plugins_info($event) {

        if (count($this->plugins) && is_array($this->plugins)) {
            $this->event = $event;
            foreach ($this->plugins as $pn => $p) {
                $this->plugin_name = $pn;
                $this->current_plugin = $p;
                $this->check_plugin();
            }
        }

        return NULL;
    }

    /**
     * 
     * @param array $plugin
     */
    private function check_plugin() {
        foreach ($this->current_plugin as $p) {
            if ($p['status'] == 1 && preg_match('/^' . str_replace('/', '\/', Now::get_controller()) . '/', $p['controller'])) {
                $this->current_plugin_now = $p;
                $this->plugin_execute();
            }
        }
    }

    private function plugin_execute() {
        foreach ($this->current_plugin_now['event_triggers'] as $event_name) {
            if (strcasecmp($event_name, $this->event) == 0) {
                if (include PLUGIN_DIRECTORY . '/' . $this->current_plugin_now['plugin_file'] . '.php') {
                    // every main plugin file has an $plugin_info varibale that is an array and is contain plugin info
                    $plugin_info['triggers'][$this->event]();
                }
            }
        }
    }

}
