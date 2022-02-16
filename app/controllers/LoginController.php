<?php

use simphplio\Controller;
use simphplio\app\models\LoginModel;

class Login extends Controller
{

    public function entrar()
    {
        if(!empty($_SESSION['auth']['user'])){

            $this->view("index");
        }

        $login = new LoginModel();
        $user = $_POST['user'];
        $senha = $_POST['password'];

        $logar = $login->verificaUser($user, $senha);

        if (!empty($logar)) {        
            $_SESSION['auth']['user'] = $user;
            $_SESSION['auth']['tipo'] = $logar['tipo'];
            $this->view("index");
        } else {
            $this->set('RETURNMESSAGE', '<div class="alert alert-danger" role="alert">  Senha errada! </div>');

            $this->view("login");
        }
    }
    public function deslogar()
    {
        $_SESSION['auth']['user'] = NULL;
       $this->set('RETURNMESSAGE', '');
       $this->view('login');
    }
}
