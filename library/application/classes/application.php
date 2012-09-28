<?php

namespace Application;

use Nerd\Design\Initializable;
use Nerd\Config;
use Nerd\Session;
use Nerd\Asset;
use Nerd\Datastore;
use Nerd\Uri;
use Nerd\Http\Response;
use Nerd\Http\Exception as HttpException;
use Nerd\Design\Structural\FrontController as Controller;
use Nerd\Form;
use Nerd\Url;

class Application extends \Nerd\Application implements Initializable
{
	public $session;
	public $cache;
	public $css;
	public $js;
	public $response;
	
	public static function __initialize()
	{
		$app = static::instance();
		$uri = Url::current()->uri();

		$css = ['css/bootstrap.css', 'css/bootstrap-responsive.css'];
		$js  = ['js/jquery.js', 'js/bootstrap.js'];

		$app->response = Response::instance();
		$app->session  = Session::instance();
		$app->cache    = Datastore::instance();
		$app->css      = Asset::collection($css);
		$app->js       = Asset::collection($js);

		// If there is no url, then we're on the home page.
		trim($uri, '/') == '' and $uri = '@@HOME';

		if ($page = Model\Page::findOneByUri($uri))
		{
			$app->response->setBody($page->title);
			return;
		}

		try
		{
			Controller::instance()->dispatch($uri, $app->response);
			return;
		}
		catch (HttpException $e)
		{
			if (!$page = Model\Page::findOneByUri($uri = '@@404'))
			{
				// Fallback to system handling.
				throw new HttpException(404, 'Page Not Found');
			}

			$app->response->setStatus(404);
			$app->response->setBody($page->title);
			return;
		}
	}
}