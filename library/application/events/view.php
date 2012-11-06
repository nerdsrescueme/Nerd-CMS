<?php

namespace Application;

use Nerd\Design\Architectural\MVC\View;

return [

    'view.setup' => function(View $view = null) {
        View::set('application', Application::instance());
		View::set('theme', Application::instance()->theme);
        View::set('flash', Application::instance()->session->flash);
    },

    'view.render' => function(View $view) {

    },
];
