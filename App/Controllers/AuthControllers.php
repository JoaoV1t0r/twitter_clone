<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AuthControllers extends Action
{

    public function autenticar()
    {
        $usuario = Container::getModel('Usuario');
        $usuario->email = $_POST['email'];
        $usuario->senha = md5($_POST['senha']);

        $usuario->autenticar();

        if ($usuario->id != '' && $usuario->nome != '') {
            session_start();

            $_SESSION['id'] = $usuario->id;
            $_SESSION['nome'] = $usuario->nome;
            print_r($_SESSION);
            header('Location: /timeline');
        } else {
            header('Location: /?login=erro');
        }
    }
    public function sair()
    {
        session_start();
        session_destroy();
        header('Location: /');
    }
}
