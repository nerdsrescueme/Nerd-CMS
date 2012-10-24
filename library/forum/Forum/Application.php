<?php

namespace Forum;

use Nerd\Uri;
use Nerd\Http\Response;
use Nerd\Design\Structural\FrontController as Controller;
use Nerd\Url;
use Nerd\Session;

class Application implements \Nerd\Design\Initializable
{
    // Traits
    use \Nerd\Design\Creational\Singleton
      , \Nerd\Design\Eventable;

    public $session;
    public $response;

    public static function __initialize()
    {
        $app = static::instance();
        $uri = Url::current()->uri();

        $app->response = Response::instance();
        $app->session  = Session::instance();

        Controller::instance()->dispatch($uri, $app->response);

        return $app->response;
    }

    /**
     * Redirect user
     *
     * @param    string          Url endpoint
     * @param    integer         Redirect status code
     * @return void
     */
    public function redirect($to = null, $status = 302)
    {
        $this->response->redirect($to, $status);
    }
}
