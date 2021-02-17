<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap
{

	protected function initRoutes()
	{

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'IndexControllers',
			'action' => 'index'
		);

		$routes['inscreverse'] = array(
			'route' => '/inscreverse',
			'controller' => 'IndexControllers',
			'action' => 'inscreverse'
		);

		$routes['registrar'] = array(
			'route' => '/registrar',
			'controller' => 'IndexControllers',
			'action' => 'registrar'
		);

		$routes['autenticar'] = array(
			'route' => '/autenticar',
			'controller' => 'AuthControllers',
			'action' => 'autenticar'
		);

		$routes['sair'] = array(
			'route' => '/sair',
			'controller' => 'AuthControllers',
			'action' => 'sair'
		);

		$routes['timeline'] = array(
			'route' => '/timeline',
			'controller' => 'AppControllers',
			'action' => 'timeline'
		);

		$routes['tweet'] = array(
			'route' => '/tweet',
			'controller' => 'AppControllers',
			'action' => 'tweet'
		);

		$routes['tweet_remover'] = array(
			'route' => '/tweet_remover',
			'controller' => 'AppControllers',
			'action' => 'tweetRemover'
		);

		$routes['quem_seguir'] = array(
			'route' => '/quem_seguir',
			'controller' => 'AppControllers',
			'action' => 'quemSeguir'
		);

		$routes['acao'] = array(
			'route' => '/acao',
			'controller' => 'AppControllers',
			'action' => 'acao'
		);

		$this->setRoutes($routes);
	}
}
