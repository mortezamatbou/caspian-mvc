<?php

/**
 * you must use regular expression to define your own global route
 * 
 */

$route['404_override'] = '404';

Route::get('', 'Welcome');
Route::get('middleware', 'Welcome/check_middleware', ['hi']);
