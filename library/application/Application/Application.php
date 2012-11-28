<?php

namespace Application;

// Nerd library
use Nerd\Design\Initializable
  , Nerd\Session
  , Nerd\Asset
  , Nerd\Datastore
  , Nerd\Uri
  , Nerd\Http\Response
  , Nerd\Http\Exception as HttpException
  , Nerd\Design\Structural\FrontController as Controller
  , Nerd\Url
  , Nerd\Design\Architectural\MVC\View

    // Auth package
  , Auth\Auth

    // Theme package
  , Theme\Theme;

class Application implements \Nerd\Design\Initializable
{
    // Traits
    use \Nerd\Design\Creational\Singleton
      , \Nerd\Design\Eventable;

    public $admin;
    public $auth;
    public $session;
    public $cache;
    public $theme;
    public $template;
    public $css;
    public $js;
    public $response;

    public static function __initialize()
    {
        $app = static::instance();
        $uri = Url::current()->uri();

        $app->response = Response::instance();
        $app->session  = Session::instance();
        $app->cache    = Datastore::instance();
        $app->theme    = Theme::instance(); // Need to make dynamic
        $app->template = $app->theme->template();
        $app->css      = Asset::collection(['bootstrap.css']);
        $app->js       = Asset::collection(['jquery.js', 'bootstrap.js']);

        // If there is no url, then we're on the home page.
        trim($uri, '/') == '' and $uri = '@@HOME';

        // If we can find the page, send it back.
        if ($page = Model\Page::findOneByUri($uri)) {
            $app->template
                ->with('main', $app->theme->view($page->layout_id))
                ->with('page', $page);

            return $app->response->setBody($app->template);
        }

        // If we can't get the page from the db, fall back to normal MVC
        // requests. All CMS specific administration pages are accessible
        // through this method, otherwise we throw a 404 error.
        try {
            Controller::instance()->dispatch($uri, $app->response);
        } catch (HttpException $e) {
            if (!$page = Model\Page::findOneByUri($uri = "@@{$e->getCode()}")) {
                // Fallback to system handling.
                throw new HttpException($e->getCode(), $uri);
            }

            $view = $app->theme->view($page->layout_id, ['e' => $e]);

            $app->template->with(['main' => $view, 'page' => $page]);

            return $app->response
                ->setStatus($e->getCode())
                ->setBody($app->template);
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
