<?php

namespace Caspian\Core\Events;

use Caspian\Core\Now;

class ServiceDefender {

    public $INI;
    public $message = '';

    function __construct() {
        $this->INI = parse_ini_file(ROOTAPP . 'config/service.ini', TRUE);
        DebugInfo::$enable = $this->INI['info']['debug'] ? TRUE : FALSE;
    }

    public function run() {
        $this->service_active();
    }

    private function service_active() {
        if (!$this->INI['root']['active']) {
            show_404('The service is being upgraded and temporarily out of reach');
        }
        $this->https_request();
    }

    private function https_request() {
        if ($this->INI['root']['https']) {
            if (!is_https()) {
                show_404('ارتباط شما باید https باشد');
            }
        }
        $this->module_service_active();
    }

    private function module_service_active() {
        if (!Now::$has_module) {
            return;
        }
        if (!isset($this->INI['module'][Now::$module_name])) {
            $this->message = Now::$module_name . ' module is not exists in service.ini';
            get_instance()->load_log();
            get_instance()->log->write($this->message, 1001);
            $debug = $this->INI['info']['debug'] ? $this : '';
            show_404('نام این ماژول در فایل ini نمی باشد');
        }
        
        if (!$this->INI['module'][Now::$module_name]) {
            $this->message = Now::$module_name . ' module is DISABLE in service.ini';
            $debug = $this->INI['info']['debug'] ? $this : '';
            get_instance()->load_log();
            get_instance()->log->write($this->message, 1001);
            show_404('این ماژول موقتا غیر فعال است');
        }
        
    }

}
