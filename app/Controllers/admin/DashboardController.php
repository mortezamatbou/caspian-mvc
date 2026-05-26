<?php

namespace App\Controllers\admin;

use Caspian\Core\Controller;

class PostController extends Controller
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
