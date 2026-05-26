<?php

namespace Caspian\Core;

use Caspian\Http\Session;


interface ControllerInterface {

    public function get_name(): string;

}


class Controller implements ControllerInterface
{

    public Session $session;
    public View $view;

    function __construct()
    {
        Registry::set('session', new Session());
        $this->session = Registry::get('session');
        $this->view = new View();

    }
    function get_name(): string {
        return "Contollers";
    }
}
