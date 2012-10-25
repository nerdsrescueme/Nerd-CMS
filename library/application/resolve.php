<?php

$folder = strpos($_SERVER['REQUEST_URI'], '/docs') !== false
	? 'docs' : 'application';

if ($folder == 'docs') {
	$namespace = 'docs';
} else {
	$namespace = 'application';
}

return function() use ($namespace)
{
	return [
		'namespace' => $namespace,
		'storage' => 'application',
	];
};