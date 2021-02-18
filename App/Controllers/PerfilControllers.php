<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class PerfilControllers extends Action
{

    public function validaAutenticacao()
    {
        session_start();
        if (!(isset($_SESSION['id'])) || $_SESSION['id'] == '' && !(isset($_SESSION['nome'])) || $_SESSION['nome'] == '') {
            header('Location: /?login=erro');
        }
    }
    public function meuPerfil()
    {
        $this->validaAutenticacao();

        //Recuperando informações do usuário
        $usuario = Container::getModel('Usuario');
        $usuario->id = $_SESSION['id'];
        $this->view->info_usuario = $usuario->getInfoUser();
        $this->view->total_tweets_usuario = $usuario->getInfoTweetsUser();
        $this->view->total_seguindo_usuario = $usuario->getInfoSeguindoUser();
        $this->view->total_seguidores_usuario = $usuario->getInfoSeguidoresUser();

        $this->render('meu_perfil');
    }

    public function mudarSenha()
    {
        $this->validaAutenticacao();

        //Recuperando informações do usuário
        $usuario = Container::getModel('Usuario');
        $usuario->id = $_SESSION['id'];
        $this->view->info_usuario = $usuario->getInfoUser();
        $this->view->total_tweets_usuario = $usuario->getInfoTweetsUser();
        $this->view->total_seguindo_usuario = $usuario->getInfoSeguindoUser();
        $this->view->total_seguidores_usuario = $usuario->getInfoSeguidoresUser();

        $this->render('mudar_senha');

    }

    public function mudarNome()
    {
        $this->validaAutenticacao();

        $usuario = Container::getModel('Usuario');
        $usuario->nome = $_POST['nome'];
        $usuario->id = $_SESSION['id'];
        $usuario->mudarNomeUsuario();
        $_SESSION['nome'] = $usuario->nome;

        header('Location: /meu_perfil');
    }

    public function mudarEmail()
    {
        $this->validaAutenticacao();

        $usuario = Container::getModel('Usuario');
        $usuario->email = $_POST['email'];
        $usuario->id = $_SESSION['id'];
        $usuario->mudarEmailUsuario();
        $_SESSION['email'] = $usuario->email;

        header('Location: /meu_perfil');
    }

    public function alterarSenha()
    {
        $this->validaAutenticacao();

        $usuario = Container::getModel('Usuario');
        $usuario->id = $_SESSION['id'];
        $senha_atual = $usuario->getSenha();
        if($senha_atual['senha'] == md5($_POST['senha_atual']) && strlen($_POST['senha_nova']) > 4){
            $usuario->senha = md5($_POST['senha_nova']);
            $usuario->mudarSenhaUsuario();
            header('Location: /mudar_senha?up=true');
        }else{
            header('Location: /mudar_senha?up=false');
        }
    }

    
}
