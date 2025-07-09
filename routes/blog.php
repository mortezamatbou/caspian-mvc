<?php

use Caspian\Router as Router;
use App\Controllers\blog\PostController;

Router::get('/', [PostController::class, 'index']);
Router::get('/posts', [PostController::class, 'posts']);
//Router::post('midd/test', [Second::class, 'index'], ['Captcha.TestMiddleware' => []]);
