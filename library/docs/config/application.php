<?php

/**
 * Application configuration
 *
 * @var     array
 */
return array(

	/**
	 * The base URL of your application - WITH TRAILING SLASH.
	 *
	 * @var     string
	 */
	'url' => 'http://localhost/nerd/docs/',

	/**
	 * The default timezone of your application. This timezone will be used
	 * when Nerd needs to generate a date, such as when writing to a log file.
	 *
	 * [!!] This is extremely important to your application. Since PHP 5.1.0
	 *      (when the date/time functions were rewritten), every call to a
	 *      date/time function will generate a E_NOTICE if the timezone isn't
	 *      valid, and/or a E_WARNING message if using the system settings or
	 *      the TZ environment variable.
	 *
	 * @see     http://php.net/timezones
	 * @var     string
	 */
	'timezone' => 'UTC',

	/**
	 * The default character encoding.
	 *
	 * @var     string
	 */
	'encoding' => 'UTF-8',

	/**
	 * Application key for this application, it is used for security and
	 * encryption. Enter a 32 digit random alpha-numeric string.
	 *
	 * You can use Nerd's Geek command line utility to generate a random string.
	 *
	 *     php ion random.string --length=32
	 *
	 * @var    string
	 */
	'securityKey' => 'asdfasdfasdfasdfasdfasdfasdfasdf',

);