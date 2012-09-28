<?php

namespace Application;

use \Nerd\Design\Architectural\MVC\View;

return [

	'view.setup' => function()
	{
		View::set('application', Application::instance());
		View::set('flash', Application::instance()->session->flash);
	},

	'view.render' => function(View $view)
	{
		
	},
];