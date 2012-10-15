<?php

namespace Application;

$app = Application::instance();

return [

    'application.startup' => function() use (&$app) {
        $app->session->referer = \Nerd\Input::server('HTTP_REFERER');
    },

    'application.shutdown' => function() use (&$app) {
        $app->session->__destruct();

        if (isset($_GET['timer'])) {
            echo '<!-- '.round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 6).' -->';
        }
    },

    'application.error' => function() use (&$app) {
        // Pass and exception by default.
    },

    'application.exception' => function() use (&$app) {

    }

];
