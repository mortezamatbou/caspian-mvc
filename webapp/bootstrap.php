<?php

require BASEPATH . 'core/Route.php';

include './webapp/application/config/modules.php';
require './webapp/application/config/routes.php';

$url_4_module = 'welcome_page';
if (isset($_GET['url'])) {
    $url_4_module = strip_tags($_GET['url']);
}

// check first segment of url in modules.php
$target = explode('/', $url_4_module);

if (is_array($target) && count($target) > 0) {
    foreach ($module as $m => $arr) {
        if (preg_match("/^$m$/i", $target[0]) && $arr['status'] == 1) {
            define('MODULE_PATH', 'modules/' . strtolower($arr['target']) . '/');
            define('MODULE_NAME', strtolower($arr['target']));
            break;
        }
    }
}

if (!defined('MODULE_PATH')) {
    define('MODULE_PATH', '');
}
if (!defined('MODULE_NAME')) {
    define('MODULE_NAME', '');
}

include './webapp/config/config_global.php';
require BASEPATH . '/config/constants.php';

// APPPATH is application folder + MODULE_NAME

require APPPATH . 'config/routes.php';
require APPPATH . 'config/config.php';
require APPPATH . 'config/hooks.php';
require APPPATH . 'config/constants.php';
require APPPATH . 'config/database.php';
require APPPATH . 'config/commons.php';
require APPPATH . 'config/plugins.php';

require BASEPATH . 'core/Hook.php';
require BASEPATH . 'core/Common.php';

$__hook = new Caspian\Core\Events\Hook();
hooking('pre_system');

$_CORE = array(
    'Log' => 'Caspian\\Core\\',
    'Now' => 'Caspian\\Core\\',
    'Security' => 'Caspian\\Core\\',
//    'Model' => 'Caspian\\Core\\Database\\',
    'Response' => 'Caspian\\Core\\Http\\',
    'Controller' => 'Caspian\\Core\\Request\\',
    'DebugInfo' => 'Caspian\\Core\\Events\\',
    'ServiceDefender' => 'Caspian\\Core\\Events\\'
);

// this load before core class and controller
if (file_exists('./vendor/autoload.php')) {
    require './vendor/autoload.php';
}

foreach ($_CORE as $file_name => $namespace) {
    load_class($file_name, '', $namespace);
}

// plugin too service framework nist
$__plugin = NULL;

require BASEPATH . 'core/RunApp.php';

