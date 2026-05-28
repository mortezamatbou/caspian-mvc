<?php

use Caspian\Core\Registry;

require_once('../caspian/config.php');
require_once('../caspian/commons.php');

require_once('../vendor/autoload.php');
require_once('../config/database.php');
require_once('../caspian/River/DB.php');

$loader = new \Caspian\Core\Loader();
Registry::set('loader', $loader);
$loader->run();
