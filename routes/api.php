<?php

use Caspian\Router as Router;
use App\Controllers\api\ApiController;

Router::get('/', [ApiController::class, 'index']);
Router::get('/markets', [ApiController::class, 'markets']);
Router::get('/markets/{market:model}', [ApiController::class, 'market']);
Router::get('/pairs/funds/etf/{country:any}/{user:model}', [ApiController::class, 'etf']);
Router::get('/{others:any}', [ApiController::class, 'others']);
