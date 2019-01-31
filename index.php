<?php

$start = microtime(TRUE);

/**
 *
 * testing
 * development
 * release
 *
 */
/**
 * define a constant that define what is environment
 */
define('CAS_ENV', isset($_SERVER['CAS_ENV']) ? $_SERVER['CAS_ENV'] : 'development');

/**
 * for sure that request cross from index.php on root of your site
 */
define('BASEPATH', './vendor/caspian/');
define('ROOTAPP', './vendor/');

/**
 * define setting for environment
 */
switch (CAS_ENV) {

    case 'development':
        ini_set('display_errors', 1);
        break;

    case 'testing':

        break;

    case 'release':
        ini_set('display_errors', 0);
        break;
}


/**
 * autoload.php -> for include essential files from framework
 */
require './vendor/bootstrap.php';

/**
 * for routing this instance is essential
 */
$boot = new Caspian();

//echo '<div class="container" style="font-size: 12px; text-align: center;">' . (microtime(TRUE) - $start) . '</div>';


