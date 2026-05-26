<?php

namespace App\Controllers\admin;

use Caspian\Core\Controller;

class DashboardController extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo 'blog.index';
    }

    public function posts()
    {
        echo 'blog.posts';
    }
}
