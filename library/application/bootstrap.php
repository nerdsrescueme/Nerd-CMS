<?php

namespace Application;

// Aliasing rules
use Nerd\Config
  , Nerd\Environment;

/**
 * Here you could do some magic for multiple applications by dynamically switching
 * the application namespace for this request. To create another application you
 * would simply create another subfolder in LIBRARY_PATH with its own Application
 * class...
 */
define('APPLICATION_NS', 'application');
define('STORAGE_PATH', LIBRARY_PATH.DS.'application/storage');

/**
 * Setup the current environment
 */
$env = Environment::$active;

//die(var_dump($env));

/**
 * The following items need to be done for every request, but since they require
 * APPLICATION_NS to be defined, the must be done in the application bootstrap file.
 *
 *   [!!] Edit at your own risk
 *
 */
error_reporting(Config::get('error.reporting'));
ini_set('display_errors', (Config::get('error.display', true) ? 'On' : 'Off'));
date_default_timezone_set(Config::get('application.timezone', 'UTC'));
\Nerd\Str::$mbString and mb_internal_encoding(Config::get('application.encoding', 'UTF-8'));

$application = Application::instance();
$application->triggerEvent('application.startup');
$application->response->send(true);
$application->triggerEvent('application.shutdown');
