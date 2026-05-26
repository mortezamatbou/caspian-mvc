<?php

use Caspian\Core\Router as Router;
use App\Controllers\LoginController;

Router::get('/', [LoginController::class, 'index']);
Router::post('/login', [LoginController::class, 'login']);

Router::get('/test/{song:model}', [LoginController::class, 'test']);
