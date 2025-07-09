<?php

namespace App\Controllers\api;

use Caspian\Controller;

class ApiController extends Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo "api.index";
    }
    
    public function others(string $others)
    {
        echo "api.others = {$others}";
    }

    public function markets()
    {
        echo "api.markets";
    }

    public function market(\App\Models\Market $market)
    {
        echo "api.markets name={$market->name()}";
    }

    public function etf(string $country, \App\Models\User $user)
    {
        echo "api.pairs.funds.etf  country={$country} - id={$user->name()}/{$user->id()}";
    }
    
    
}
