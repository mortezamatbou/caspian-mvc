<?php

namespace App\Controllers\admin;

use Caspian\Controller;

/**
 * @property AdminLoginModel model
 */
class AdminLogin extends Controller {

    function __construct()
    {
        parent::__construct();
        $this->model = new AdminLoginModel();
    }

    public function login()
    {
        $numLogin = $this->model->getCountLogin();
        if ($numLogin['cunlogin'] >= MAX_LOGIN_COUNT) {
            exit('call support team');
        }

        $params = [];
        $checkLogin = Model::sessionLogin();
        if (empty($checkLogin)) {
            if (!empty($_POST) and !empty($_SERVER['HTTP_REFERER'])) {
                $status = $this->model->verifyUser($_POST);
                if ($status == 0) {
                    header('location: /admin/dashboard');
                    exit();
                }
                $params = ['error' => $status];
            }

            $this->viewAdmin('login/login', $params, 1, 1);
        } else {
            header('location: /admin/dashboard');
            exit();
        }
    }
}
