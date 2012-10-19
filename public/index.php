<?php

/**
 * If an application bootstrap file exists, execute it... If not, use
 * the default nerd bootstrapper.
 */
if (file_exists(__DIR__.'/../library/application/bootstrap.php')) {
	include '../library/application/bootstrap.php';
} else {
	include '../library/nerd/bootstrap.php';
}