<?php

namespace App\Controllers\blog;

use Caspian\Controller;

class PostController extends Controller {

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
