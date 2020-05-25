<?php

class BlogIndex extends Controller {
    
    function __construct() {
        parent::__construct();
    }

    function index() {
        $this->view->render('index');
    }
    
    
}

