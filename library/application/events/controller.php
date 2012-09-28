<?php

namespace Application;

use \Nerd\Design\Architectural\MVC\Controller;
use \Nerd\Design\Architectural\MVC\View;
use \Nerd\Input;

return [

	'controller.setup' => function(Controller $controller)
	{
		$session = Application::instance()->session;

		if (!Input::protect($session->get('application.csrf')))
		{
			throw new \Nerd\Http\Exception(403);
		}

		$controller->application = Application::instance();
		$controller->session     = $session;
		$controller->flash       = $session->flash;
		$controller->response    = Application::instance()->response;
		$controller->template    = new View('template');
	},

];