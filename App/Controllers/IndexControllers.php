<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class IndexControllers extends Action
{

	public function index()
	{
		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';

		$this->render('index', 'layout_off');
	}

	public function inscreverse()
	{
		$this->view->usuario = array(
			'nome' => '',
			'email' => '',
			'senha' => '',
		);
		$this->view->erroCadastro = false;

		$this->render('inscreverse', 'layout_off');
	}

	public function registrar()
	{
		//Receber os dados do formulario
		$usuario = Container::getModel('Usuario');
		$usuario->nome = $_POST['nome'];
		$usuario->email = $_POST['email'];
		$usuario->senha = md5($_POST['senha']);

		if ($usuario->validarCadastro() && count($usuario->getUsuarioEmail()) == 0 && count($usuario->getUsuarioNome()) == 0) {
			//Sucesso
			$usuario->salvar();
			$this->render('cadastro');
		} else {
			//Erro
			$this->view->usuario = array(
				'nome' => $_POST['nome'],
				'email' => $_POST['email'],
				'senha' => $_POST['senha']
			);
			$this->view->erroCadastro = true;
			$this->render('inscreverse', 'layout_off');
		}
	}
}
