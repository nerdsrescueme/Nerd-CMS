<?php

/**
 * Application controller namespace. The entire Application namespace is merely
 * a placeholder namespace for what can eventually be your custom application.
 * It includes some basic examples of existing functionality, as well as a
 * generic baseline for you to elaborate on.
 *
 * @package    Application
 * @subpackage Controller
 */
namespace Application\Controller;

// Aliasing rules
use Nerd\Design\Architectural\MVC\Controller;
use Nerd\Design\Architectural\MVC\View;

/**
 * Home controller
 *
 * This controller is a simplified example of a basic controller implementation.
 * It contains examples of how to set response bodies, and statuses.
 *
 * @package    Application
 * @subpackage Controller
 */
class Home extends Controller {

	/**
	 * A basic index page
	 *
	 * @return   void     No value is returned
	 */
	public function actionIndex()
	{
		return (new View('template'))->partial('content', 'home/index');
	}
}