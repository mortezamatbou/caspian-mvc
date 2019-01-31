<?php


class Welcome extends Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    function index() {
        echo '<h1>Welcome to caspian mvc framework!</h1>Index of site';
    }
    
}

