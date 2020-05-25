<?php

use Caspian\Core\Routing\Route;

/**
 * you must use regular expression to define your own global route
 * 
 */

$route['404_override'] = '404';

Route::get('', 'BlogIndex');