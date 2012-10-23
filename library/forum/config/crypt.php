<?php

/**
 * Cryptographic configurations
 *
 * @var    array
 */
return [

	/**
	 * Defauilt Crypt driver
	 *
	 * The default driver to be utilized by your application in the event a
	 * specific driver isn't called.
	 *
	 * Available drivers: 'xcrypt', 'mcrypt'
	 *
	 * @var    string
	 */
	'driver' => 'xcrypt',

	/**
	 * Unique encryption key (shared key, or salt)
	 *
	 * Your applications encryption key can be any possible string that is
	 * completely unique and secret. This is used to generate secure,
	 * encryptions and hashes that are unique to your application only.
	 *
	 * @var    string
	 */
	'key' => 'replace-me',

	/**
	 * The hash type to use in static::$hash
	 *
	 * Valid values are: sha1, md5
	 *
	 * @var    string
	 */
	'hasher' => 'sha1',

	/**
	 * Mcrypt driver configuration settings
	 *
	 * These values are passed into the mcrypt driver for encryption and
	 * decryption of strings
	 *
	 * @var    array
	 */
	'mcrypt' => [
		'cipher' => 'rijndael-256',
		'mode'   => 'cbc',
	],

];