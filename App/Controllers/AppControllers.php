<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppControllers extends Action
{
    public function validaAutenticacao()
    {
        session_start();
        if (!(isset($_SESSION['id'])) || $_SESSION['id'] == '' && !(isset($_SESSION['nome'])) || $_SESSION['nome'] == '') {
            header('Location: /?login=erro');
        }
    }

    public function timeline()
    {
        $this->validaAutenticacao();

        //Recuperar os Tweets
        $tweet = Container::getModel('Tweet');
        $tweet->id_usuario = $_SESSION['id'];

        //Variaveis de paginação

        $total_registro_pagina = 15;
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina - 1) * $total_registro_pagina;

        //$tweets = $tweet->getAll();
        $tweets = $tweet->getPorPagina($total_registro_pagina, $deslocamento);

        $total_tweets = $tweet->getTotalTweets();
        $this->view->total_paginas = ceil($total_tweets['total'] / $total_registro_pagina);
        $this->view->pagina_ativa = $pagina;
        $this->view->tweets = $tweets;

        //Recuperando informações do usuário
        $usuario = Container::getModel('Usuario');
        $usuario->id = $_SESSION['id'];
        $this->view->info_usuario = $usuario->getInfoUser();
        $this->view->total_tweets_usuario = $usuario->getInfoTweetsUser();
        $this->view->total_seguindo_usuario = $usuario->getInfoSeguindoUser();
        $this->view->total_seguidores_usuario = $usuario->getInfoSeguidoresUser();

        $this->render('timeline');
    }

    public function tweet()
    {
        $this->validaAutenticacao();

        $tweet = Container::getModel('Tweet');

        $tweet->tweet = $_POST['tweet'];
        $tweet->id_usuario = $_SESSION['id'];

        $tweet->salvar();

        header('Location: /timeline');
    }

    public function tweetRemover()
    {
        $this->validaAutenticacao();

        $tweet = Container::getModel('Tweet');

        $tweet->id = $_GET['id'];

        $tweet->remover();

        header('Location: /timeline');
    }

    public function quemSeguir()
    {
        $this->validaAutenticacao();

        $usuarios = array();

        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

        if ($pesquisarPor != '') {
            $usuario = Container::getModel('Usuario');
            $usuario->nome = $_GET['pesquisarPor'];
            $usuario->id = $_SESSION['id'];
            $usuarios = $usuario->getAll();
        }
        $this->view->usuarios = $usuarios;

        //Recuperando informações do usuário
        $usuario = Container::getModel('Usuario');
        $usuario->id = $_SESSION['id'];
        $this->view->info_usuario = $usuario->getInfoUser();
        $this->view->total_tweets_usuario = $usuario->getInfoTweetsUser();
        $this->view->total_seguindo_usuario = $usuario->getInfoSeguindoUser();
        $this->view->total_seguidores_usuario = $usuario->getInfoSeguidoresUser();

        $this->render('quemSeguir');
    }

    public function acao()
    {
        $this->validaAutenticacao();

        //acao
        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        //id_usuario
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        $usuario = Container::getModel('Usuario');
        $usuario->id = $_SESSION['id'];

        if ($acao == 'seguir') {
            //Seguir
            $usuario->seguirUsuario($id_usuario_seguindo);
        } else if ($acao = 'deixarSeguir') {
            //Deixar de Seguir
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        }

        header('Location: /quem_seguir');
    }
}
