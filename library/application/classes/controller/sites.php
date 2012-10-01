<?php

namespace Application\Controller;

// Aliasing rules
use Nerd\Design\Architectural\MVC\Controller;
use Nerd\Design\Architectural\MVC\View;
use Application\Model\Site;
use Nerd\Form\Field\Submit;
use Nerd\Form\Field\Reset;
use Nerd\Input;
use Nerd\Session;
use Nerd\Url;
use Nerd\Format;

class Sites extends Controller
{
	public function actionIndex()
	{
		return $this->template->partial('content', 'sites/index', [
			'sites' => Site::findAll('SELECT * FROM nerd_sites'),
		]);
	}

	public function actionView($id = null)
	{
		return $this->template->partial('content', 'sites/view', [
			'site' => Site::findOneById($id),
		]);
	}

	public function actionCreate()
	{
		$site = new Site();

		// I need some form of post checking
		if (Input::$method === 'post')
		{
			try
			{
				$success = false;

				$site->host = Input::post('site.host');
				$site->theme = Input::post('site.theme');
				$site->active = (bool) Input::post('site.active');
				$site->maintaining = (bool) Input::post('site.maintaining');
				$site->description = Input::post('site.description');
				
				if ($site->hasErrors())
				{
					throw new \OutOfBoundsException('');
				}

				// Attempt database write.
				list($success, $count) = $site->insert();

				// If database wrote and actually added a record.
				if ($success and $count > 0)
				{
					$this->flash->set('success', 'Successfully added your new site to the database.');
					$this->application->redirect(Url::site('/sites'));
				}
				else
				{
					$this->flash->set('error', 'Unable to save your data.');
				}
			}
			catch (\Nerd\DB\Exception $e) // Database exception
			{
				$this->flash->set('error', 'There was a database error…');
			}
			catch (\OutOfBoundsException $e) // Validation exception
			{
				$form = $site->form($this->form())->action("/sites/create");

				foreach($site->errors as $field => $errors)
				{
					$form->findByAttribute('id', "site_$field")->wrap('<div class="control-group error">', '</div>');
				}

				$this->flash->set('error', $site->errors);
			}
		}

		if (!isset($form))
		{
			$form = $site->form($this->form())->action(Url::site('/sites/create'));
		}
		
		$form->container('div',
			(new Reset(['class' => 'btn'])),
			(new Submit(['class' => 'btn']))
		)->class('form-actions');
		
		return $this->template->partial('content', 'sites/new', [
			'form' => $form,
			'site' => $site,
		]);
	}

	public function actionUpdate($id = null)
	{
		$site = Site::findOneById($id);

		// I need some form of post checking
		if (Input::$method === 'post')
		{
			try
			{
				$success = false;

				$site->host = Input::post('site.host');
				$site->theme = Input::post('site.theme');
				$site->active = (bool) Input::post('site.active', false);
				$site->maintaining = (bool) Input::post('site.maintaining', false);
				$site->description = Input::post('site.description');

				if ($site->hasErrors())
				{
					throw new \OutOfBoundsException('');
				}

				// Attempt database write.
				list($success, $count) = $site->update();

				// If database wrote and actually added a record.
				if ($success and $count > 0)
				{
					$this->flash->set('success', 'Successfully updated your site.');
					$this->application->redirect(Url::site('/sites'));
				}
				else
				{
					$this->flash->set('warning', 'There were no changes to save.');
				}
			}
			catch (\Nerd\DB\Exception $e) // Database exception
			{
				$this->flash->set('error', 'There was a database error...');
			}
			catch (\OutOfBoundsException $e) // Validation exception
			{
				$form = $site->form($this->form())->action(Url::site("/sites/update/$id"));

				foreach($site->errors as $field => $errors)
				{
					$form->findByAttribute('id', "site_$field")->wrap('<div class="control-group error">', '</div>');
				}

				$this->flash->set('error', $site->errors);
			}
		}

		if (!isset($form))
		{
			$form = $site->form($this->form())->action(Url::site("/sites/update/$id"));
		}

		$form->container('div',
			(new Reset(['class' => 'btn'])),
			(new Submit(['class' => 'btn']))
		)->class('form-actions');

		return $this->template->partial('content', 'sites/update', [
			'form' => $form,
			'site' => $site,
		]);
	}

	public function actionDelete($id = null)
	{
		try
		{
			$site = Site::findOneById($id);

			list($success, $count) = $site->delete();
		}
		catch(\Nerd\DB\Exception $e)
		{
			$this->flash->set('warning', "There was a database error...");
			$this->application->redirect(Url::site('/sites'));
		}

		if ($success and $count > 0)
		{
			$this->flash->set('success', "<strong>{$site->host}</strong> has been deleted.");
		}
		else
		{
			$this->flash->set('error', "<strong>{$site->host}</strong> could not be deleted.");
		}

		$this->application->redirect(Url::site('/sites'));
	}

	private function form()
	{
		\Nerd\Form\Label::defaultAttribute('class', 'control-label');
	
		return (new \Nerd\Form())
		      ->class('form-horizontal')
		      ->method('post')
		      ->wrap('<div class="control-group">', '</div>')
			  ->wrapFields('<div class="controls">', '</div>');;
	}
}