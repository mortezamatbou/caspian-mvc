<?php

use Caspian\Router as Router;
use App\Controllers\HomeController;

Router::get('/', [HomeController::class, 'index']);
Router::get('/contact-us', [HomeController::class, 'contact_us']);
//Router::post('midd/test', [Second::class, 'index'], ['Captcha.TestMiddleware' => []]);
