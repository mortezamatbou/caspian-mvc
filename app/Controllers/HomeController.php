<?php

namespace App\Controllers;

use Caspian\Controller;

class HomeController extends Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo 'web.index';
    }

    public function contact_us()
    {
        echo 'web.contact_us';
    }
}
