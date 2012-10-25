<?php

// Aliasing rules
use \Atom\Source;
use \Atom\View;
use \Atom\View\Model;
use \Atom\Uri;

// Include markdown parser… NOT PERMANENT
require LIBRARY_PATH.'/vendor/markdown.php';

/**
* URI Handling
*
* Grab the elements of the URI, remove 'docs' from the array. From there the first
* element will be the section, with the following elements identifying the resource
* that needs to be loaded.
*/
$segments = Uri::active()->getSegments();

array_shift($segments); // Remove 'docs'

$section = array_shift($segments);

/**
* Classes Section
*
* This section will automatically parse the requested class resource at runtime and
* render a view with that information. The view used comes standard with Atom, but
* you may also alter it to suit your specific development needs.
*/
if($section == 'classes')
{
	$class = '';

	foreach($segments as $segment)
	{
		$class .= '\\'.ucfirst($segment); 
	}

	// Needs 404 handling with try…catch
	$class = Source::getClass($class);

	echo View::make('docs/class', new Model('docs/class', array($class)))->render();

	exit();
}

/**
* Configs Section
*
* This section will automatically parse the requested config resource at runtime and
* render a view with the information.
*/
if($section == 'configs')
{
	echo 'Process and show config view';
	exit();
}

/**
* Examples Section
*
* This section is a little different than those above. This section will load in
* markdown encoded example pages from the Atom GitHub account. To do so it accesses
* the GH URL and pulls the information down, and processes it for display using the
* docs layout view.
*/
if($section == 'examples')
{
	echo 'Process and show example';
	exit();
}

