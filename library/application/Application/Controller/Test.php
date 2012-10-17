<?php

namespace Application\Controller;

// Aliasing rules
use Nerd\Design\Architectural\MVC\Controller;
use Nerd\Design\Architectural\MVC\View;

class Test extends Controller
{
    public function actionIndex()
    {
        die('index');
    }

    public function actionModelform()
    {
        \Nerd\Form\Label::defaultAttribute('class', 'control-label');

        $form = (new \Nerd\Form())
              ->class('form-horizontal')
              ->wrap('<div class="control-group">', '</div>')
              ->wrapFields('<div class="controls">', '</div>');;

        //$form = \Application\Model\State::findOneByCode('NJ')->form();
        \Application\Model\Page::findOne('SELECT * FROM nerd_pages')->form($form);

        return (new View('template'))->partial('content', 'test/form', [
            'form' => $form,
        ]);
    }

    public function actionForm()
    {
        \Nerd\Form\Label::defaultAttribute('class', 'control-label');

        $form = (new \Nerd\Form());

        $form->action('/this/url')
             ->class('form-horizontal')
             ->wrap('<div class="control-group">', '</div>')
             ->wrapFields('<div class="controls">', '</div>');

        $selectOptions = [
            'one' => 'One',
            'two' => 'Two',
        ];

        $form->fieldset(
            $form->field('text', [], null)->id('my_id')->label('Username'),
            $form->field('password', [], null)->class('required')->label('Password'),
            $form->field('checkbox', [], null)->checked(true)->label('Stay logged in?', ['class' => 'checkbox']),
            $form->field('select', ['options' => $selectOptions], null)->label('Select Something')
        )->legend('Login');

        $form->container('div',
            (new \Nerd\Form\Label('Some Text'))->class('control-label'),
            (new \Nerd\Form\Html('div',
                $form->field('text', [], null)->class('span2')->wrap(false)->wrapField(false),
                $form->field('text', [], null)->class('span2')->wrap(false)->wrapfield(false)
            ))->class('controls controls-row')
        )->class('control-group');

        $form->container('div',
            (new \Nerd\Form\Html('div',
                $form->field('reset', [], null)->class('btn btn-danger')->value('Reset')->wrap(false)->wrapField(false),
                $form->field('submit', [], null)->class('btn btn-primary')->value('Submit')->wrap(false)->wrapField(false)
            ))->class('pull-right')
        )->class('form-actions');

        $username = $form->findByAttribute('id', 'my_id');
        $username->class = 'in-addition';

        $form->findByType('select')->class = 'late-addition';

        return (new View('template'))->partial('content', 'test/form', [
            'form' => $form,
        ]);
    }
}
