<?php

/**
 * Application Controllers
 *
 * @package NerdCMS
 * @subpackage Controllers
 */
namespace Application\Controller;

// Aliasing rules
use Nerd\Design\Architectural\MVC\Controller;

/**
 * Application Base Controller
 *
 * Serves as a Base controller for all other Nerd CMS application controllers.
 * It sets up a few common methods and assets to inject into each subsequent
 * controller.
 *
 * @package NerdCMS
 * @subpackage Controllers
 */
class Base extends Controller
{
    /**
     * Extension of the default before method
     *
     * @return void
     */
    public function before()
    {
        parent::before();

        // Embed CSS
        $this->application->css->add('tablecloth.css');

        // Embed JS
        $this->application->js->add('jquery.metadata.js');
        $this->application->js->add('jquery.tablesorter.min.js');
        $this->application->js->add('jquery.tablecloth.js');
        $this->application->js->add('application.js');
    }

    /**
     * Generate a form object and set it up in a common fashion
     *
     * @return Nerd\Form
     */
    protected function form()
    {
        \Nerd\Form\Label::defaultAttribute('class', 'control-label');

        return (new \Nerd\Form())
              ->class('form-horizontal')
              ->method('post')
              ->wrap('<div class="control-group">', '</div>')
              ->wrapFields('<div class="controls">', '</div>');;
    }
}
