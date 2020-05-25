<?php

class Controller {
    /**
     * Controller is a important class in Caspian Framework, this class connect different section of framework, and ...
     * 
     * @var $view an instance of View class that is accessible for every class that drive from Controller class
     * 
     * @var $load an instance of Load class that it can be common and essential jobs and method like attach a library and ...
     * 
     * @var $input an instance of Input class, that class work with requests GET and POST and return this value
     * 
     * @var $session an instance of Session class that it, when initialize that load_session() called, otherwise Session class not load by framework
     * 
     * @var $cookie an instance of Cookie class, that class provide methods for 
     * 
     * @var $log an instance of Log class, that this class provide methods for working with loging and log file manipulating
     * 
     * @var $twig an instance of Twig template engine
     * 
     */

    /**
     * @var \View $view
     */
    public $view;

    /**
     * @var \Load $load
     */
    public $load;

    /**
     * @var \Input
     */
    public $input;

    /**
     * @var \Session $session
     */
    public $session;

    /**
     * @var \Cookie
     */
    public $cookie;

    /**
     * @var \Log
     */
    public $log;

    /**
     *
     * @var \Response
     */
    public $response;

    /**
     * @var \DatabaseTools
     */
    public $db;

    /**
     * @var \Plugin
     */
    public $plugin;

    /**
     *
     * @var \Twig\Environment
     */
    public $twig;

    /**
     * @var \Controller
     */
    private static $instance;

    /**
     * initialized $view and $load instance from herself classes
     * @return void
     */
    function __construct() {
        global $config;

        self::$instance = & $this;

        $this->view = load_class('View', '');
        $this->load = load_class('Load', '');
        $this->input = load_class('Input', '');
        $this->plugin = load_class('Plugin', '');
        $this->response = load_class('Response', '');

        $twig_path = VIEWS_PATH . (isset($config['template']) && $config['template'] ? '/' . $config['template'] : '');

        if (file_exists('./vendor/autoload.php')) {
            $twig_loader = new \Twig\Loader\FilesystemLoader($twig_path);
            $this->twig = new \Twig\Environment($twig_loader, ['cache' => APPPATH_ . '/twig']);
        }
    }

    /**
     * any time this method called in controller constructor, $session to be initial and it is usable in controller
     */
    function load_session() {
        /**
         * because Session class in default unattached, before use it must be attached
         */
        $this->session = load_class('Session', '');
    }

    function load_cookie() {
        /**
         * because Cookie class in default unattached, before use it must be attached
         */
        $this->cookie = load_class('Cookie', '');
    }

    function load_log() {
        $this->log = load_class('Log', '');
    }

    function load_database() {
        $o = load_class('Model', '');
        $this->log = load_class('Log', '');
        $this->db = $o->db;
    }

    public static function &get_instance() {
        return self::$instance;
    }

}
