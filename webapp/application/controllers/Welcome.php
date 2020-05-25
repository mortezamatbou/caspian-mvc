<?php

class Welcome extends Controller {
    
    function __construct() {
        parent::__construct();
    }

    function index() {
        
        $this->view->render('index', ['message' => 'Welcome to Caspian']);
        
    }

    function check_middleware() {
        
    }
    
}
