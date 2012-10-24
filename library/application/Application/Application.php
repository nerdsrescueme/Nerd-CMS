<?php

namespace Application;

use Nerd\Design\Initializable;
use Nerd\Session;
use Nerd\Asset;
use Nerd\Datastore;
use Nerd\Uri;
use Nerd\Http\Response;
use Nerd\Http\Exception as HttpException;
use Nerd\Design\Structural\FrontController as Controller;
use Nerd\Url;

use Auth\Auth;

class Application implements \Nerd\Design\Initializable
{
    // Traits
    use \Nerd\Design\Creational\Singleton
      , \Nerd\Design\Eventable;

    public $me;
    public $auth;
    public $session;
    public $cache;
    public $theme;
    public $css;
    public $js;
    public $response;

    public static function __initialize()
    {
        $app = static::instance();
        $uri = Url::current()->uri();

        $user = Model\User::findOneById(1);

        $app->auth     = new Auth($user);
        $app->me       = $app->auth->user;

        $app->response = Response::instance();
        $app->session  = Session::instance();
        $app->cache    = Datastore::instance();
        $app->theme    = \Theme\Theme::instance('default');
        $app->css      = Asset::collection(['css/bootstrap.css']);
        $app->js       = Asset::collection(['js/jquery.js', 'js/bootstrap.js']);

        // If there is no url, then we're on the home page.
        trim($uri, '/') == '' and $uri = '@@HOME';

        if ($page = Model\Page::findOneByUri($uri)) {
            $app->response->setBody($page->title);

            return $app->response;
        }

        try {
            Controller::instance()->dispatch($uri, $app->response);

            return $app->response;
        } catch (HttpException $e) {
            if (!$page = Model\Page::findOneByUri($uri = '@@404')) {
                // Fallback to system handling.
                throw new HttpException(404, 'Page Not Found');
            }

            $app->response->setStatus(404);
            $app->response->setBody($page->title);

            return $app->response;
        }
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
