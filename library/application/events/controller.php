<?php

namespace Application;

use Nerd\Design\Architectural\MVC\Controller
  , Nerd\Design\Architectural\MVC\View
  , Nerd\Input
  , Nerd\Session;

return [

    'controller.setup' => function(Controller $controller) {
        $session = Session::instance();

        $controller->application = Application::instance();
        $controller->session     = $session;
        $controller->flash       = $session->flash;
        $controller->response    = Application::instance()->response;
        $controller->template    = new View('template');
    },

];
