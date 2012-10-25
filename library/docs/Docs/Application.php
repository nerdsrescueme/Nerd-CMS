<?php

namespace Docs;

use Nerd\Asset;
use Nerd\Datastore;
use Nerd\Http\Response;
use Nerd\Url;
use Nerd\Design\Architectural\MVC\View;
use dflydev\markdown\MarkdownExtraParser as MD;

class Application implements \Nerd\Design\Initializable
{
    // Traits
    use \Nerd\Design\Creational\Singleton
      , \Nerd\Design\Eventable;

    public $cache;
    public $css;
    public $js;
    public $response;

    public static function __initialize()
    {
        $app = static::instance();
        $url = Url::current();

		$app->response = Response::instance();

        $app->cache = Datastore::instance();
        $app->css   = Asset::collection(['css/bootstrap.css']);
        $app->js    = Asset::collection(['js/jquery.js', 'js/bootstrap.js']);

		$segments = $url->segments();
		$section  = array_shift($segments);

		View::set('md', new MD());

		switch($section) {
			case 'home':
				$app->response->setBody('Home!');
				break;
			case 'classes':
				$namespace = array_shift($segments);

				foreach ($segments as $k => $s) {
					$segments[$k] = ucfirst($s);
				}

				$class  = join('\\', $segments);
				$class  = ucfirst($namespace).'\\'.$class;
				$class  = \Nerd\Source::getClass($class);

				$app->response->setBody(
					(new View('class', ['class' => $class]))->render()
				);

				break;
			case 'examples' :
				$app->response->setBody('Example docs');
				break;
			default:
				throw new \Nerd\Http\Exception();
		}
    }
}
