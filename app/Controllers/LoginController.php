<?php

namespace App\Controllers;

use App\Models\Song;
use App\Requests\LoginRequest;
use Caspian\Core\Controller;


class LoginController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // $this->view->render('login', ['title' => 'Login To Panel']);
        echo "Hello World";
    }

    public function test(Song $song, LoginRequest $login)
    {
        pre_print($login->route_mode());
        pre_print($song->get_song());
    }

    public function login(LoginRequest $form)
    {
        echo 'web.contact_us';
    }
}
