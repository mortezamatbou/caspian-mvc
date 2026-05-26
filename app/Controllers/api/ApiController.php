<?php

namespace App\Controllers\api;

use Caspian\Core\Controller;

class ApiController extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo "api.index";
    }
}
