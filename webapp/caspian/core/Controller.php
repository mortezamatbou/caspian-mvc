<?php

namespace Caspian\Core\Request;

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
     * @var $log an instance of Log class, that this class provide methods for working with logging and log file manipulating
     * 
     * @var $twig an instance of Twig template engine
     * 
     */

    /**
     * @var \Caspian\Core\View $view
     */
    public $view;

    /**
     * @var \Caspian\Core\Load $load
     */
    public $load;

    /**
     * @var \Caspian\Core\Http\Input
     */
    public $input;

    /**
     * @var \Caspian\Core\Session $session
     */
    public $session;

    /**
     * @var \Caspian\Core\Cookie
     */
    public $cookie;

    /**
     * @var \Caspian\Core\Log
     */
    public $log;

    /**
     *
     * @var \Caspian\Core\Http\Response
     */
    public $response;

    /**
     * @var \Caspian\Core\Database\DatabaseTools
     */
    public $db;

    /**
     * @var \Caspian\Core\Events\Plugin
     */
    public $plugin;

    /**
     *
     * @var \Twig\Environment
     */
    public $twig;

    /**
     * @var \Caspian\Core\Request\Controller
     */
    private static $instance;

    /**
     * initialized $view and $load instance from herself classes
     * @return void
     */
    function __construct() {
        global $config;

        self::$instance = & $this;

        $this->view = load_class('View', '', 'Caspian\\Core\\');
        $this->load = load_class('Load', '', 'Caspian\\Core\\');
        $this->input = load_class('Input', '', 'Caspian\\Core\\Http\\');
        $this->plugin = load_class('Plugin', '', 'Caspian\\Core\\Events\\');
        $this->response = load_class('Response', '', 'Caspian\\Core\\Http\\');

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
        $this->session = load_class('Session', '', 'Caspian\\Core\\');
    }

    function load_cookie() {
        /**
         * because Cookie class in default unattached, before use it must be attached
         */
        $this->cookie = load_class('Cookie', '', 'Caspian\\Core\\');
    }

    function load_log() {
        $this->log = load_class('Log', '', 'Caspian\\Core\\');
    }

    function load_database() {
        $o = load_class('Model', '', 'Caspian\\Core\\Database\\');
        $this->log = load_class('Log', '', 'Caspian\\Core\\');
        $this->db = $o->db;
    }

    public static function &get_instance() {
        return self::$instance;
    }

}
