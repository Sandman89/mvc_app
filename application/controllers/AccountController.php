<?php

namespace application\controllers;

use application\models\User;
use core\Controller;

class AccountController extends Controller
{
    public function actionLogin()
    {
         $auth = User::authenticate($_POST);
         if ($auth)
              $this->redirect('/');
         return $this->view->render('account/login');
    }
    public function actionLogout(){
        session_unset();
        session_destroy();
        $this->redirect('/');
    }

}