<?php

namespace Application\Controller;

// Aliasing rules
use Nerd\Design\Architectural\MVC\Controller
  , Nerd\Design\Architectural\MVC\View
  , Application\Model\Page
  , Nerd\Form\Field\Submit
  , Nerd\Form\Field\Reset
  , Nerd\Input
  , Nerd\Session
  , Nerd\Url
  , Nerd\Format;

class Pages extends Controller
{
	public function before()
	{
		parent::before();

		$this->bindEvent('controller.teardown', function($controller)
		{
			$controller->template->set('lastSearch', $controller->lastSearch());
		});
	}
	public function actionIndex()
	{
		// Set return state for pages (last search function)
		$this->session->delete('page.lastSearch');

		return $this->template->partial('content', 'pages/index', [
			'pages' => Page::findAll('SELECT * FROM nerd_pages'),
		]);
	}

	public function actionView($id = null)
	{
		return $this->template->partial('content', 'pages/view', [
			'page' => Page::findOneById($id),
		]);
	}

	public function actionSearch()
	{
		$query = Input::get('q');
		$pages = Page::search($query);

		// Set the last search
		$this->session->set('page.lastSearch', $query);

		if (Input::$ajax)
		{
			return $pages->to('json');
		}

		return $this->template->partial('content', 'pages/index', [
			'pages' => $pages,
			'term'  => $query
		]);
	}

	public function actionCreate()
	{
		$page = new Page();

		// I need some form of post checking
		if (Input::post('page.title', false))
		{
			try
			{
				$success = false;

				$page->site_id = (int) Input::post('page.site_id');
				$page->layout_id = Input::post('page.layout_id');
				$page->title = Input::post('page.title');
				$page->subtitle = Input::post('page.subtitle');
				$page->description = Input::post('page.description');
				$page->status = Input::post('page.status');
				$page->priority = Input::post('page.priority');
				$page->change_frequency = Input::post('page.change_frequency');

				$uri = Input::post('page.uri');
				
				if (strpos($uri, '@@') !== false)
				{
					$page->errors['uri'][] = 'You may not add your own special "@@" pages, only the system may do that.';
				}
				else
				{
					$page->uri = $uri;
				}
				
				// If there are validation errors, fail.
				if ($page->hasErrors())
				{
					throw new \OutOfBoundsException('');
				}

				// Attempt database write.
				list($success, $count) = $page->insert();

				// If database wrote and actually added a record.
				if ($success and $count > 0)
				{
					$this->flash->set('success', "Successfully added {$page->title}.");
					$this->application->redirect(Url::site('/pages'));
				}
				else
				{
					$this->flash->set('error', "Unable to save <em>{$page->title}</em>.");
				}
			}
			catch (\Nerd\DB\Exception $e) // Database exception
			{
				$this->flash->set('error', 'There was a database error…');
			}
			catch (\OutOfBoundsException $e) // Validation exception
			{
				$form = $page->form($this->form())->action("/pages/create");

				foreach($page->errors as $field => $errors)
				{
					$form->findByAttribute('id', "page_$field")->wrap('<div class="control-group error">', '</div>');
				}

				$this->flash->set('error', $page->errors);
			}
		}

		if (!isset($form))
		{
			$form = $page->form($this->form())->action(Url::site('/pages/create'));
		}
		
		$form->container('div',
			(new Reset(['class' => 'btn btn-danger'])),
			(new Submit(['class' => 'btn btn-primary']))
		)->class('form-actions');
		
		return $this->template->partial('content', 'pages/new', [
			'form' => $form,
			'page' => $page,
		]);
	}

	public function actionUpdate($id = null)
	{
		$page = Page::findOneById($id);

		// I need some form of post checking
		if (Input::$method === 'post')
		{
			try
			{
				$success = false;

				$page->site_id = (int) Input::post('page.site_id');
				$page->layout_id = Input::post('page.layout_id');
				$page->title = Input::post('page.title');
				$page->subtitle = Input::post('page.subtitle');
				$page->description = Input::post('page.description');
				$page->status = Input::post('page.status');
				$page->priority = Input::post('page.priority');
				$page->change_frequency = Input::post('page.change_frequency');

				$uri = Input::post('page.uri') and $page->uri = $uri;

				if ($page->hasErrors())
				{
					throw new \OutOfBoundsException('');
				}

				list($success, $count) = $page->update();

				if ($success and $count > 0)
				{
					$this->flash->set('success', "Successfully updated <em>{$page->title}</em>.");
					$this->application->redirect(Url::site("/pages/view/$id"));
				}
				else
				{
					$this->flash->set('warning', "No changes were made to <em>{$page->title}</em>.");
					$this->application->redirect(Url::site('/pages'));
				}
			}
			catch (\Nerd\DB\Exception $e) // Database exception
			{
				$this->flash->set('error', 'There was a database error...');
			}
			catch (\OutOfBoundsException $e) // Validation exception
			{
				$form = $page->form($this->form())->action(Url::site("/pages/update/$id"));

				foreach($page->errors as $field => $errors)
				{
					$form->findByAttribute('id', "page_$field")->wrap('<div class="control-group error">', '</div>');
				}

				$this->flash->set('error', $page->errors);
			}
		}

		if (!isset($form))
		{
			$form = $page->form($this->form())->action(Url::site("/pages/update/$id"));
		}

		$form->container('div',
			(new Reset(['class' => 'btn btn-danger'])),
			(new Submit(['class' => 'btn btn-primary']))
		)->class('form-actions');

		return $this->template->partial('content', 'pages/update', [
			'form' => $form,
			'page' => $page,
		]);
	}

	public function actionDelete($id = null)
	{
		try
		{
			$page = Page::findOneById($id);

			list($success, $count) = $page->delete();
		}
		catch(\Nerd\DB\Exception $e)
		{
			$this->flash->set('warning', "You may not delete <em>{$page->title}</em>. It is a <strong>Nerd</strong> protected file.");
			$this->application->redirect(Url::site('/pages'));
		}

		if ($success and $count > 0)
		{
			$this->flash->set('success', "<em>{$page->title}</em> has been deleted.");
		}
		else
		{
			$this->flash->set('error', "<em>{$page->title}</em> could not be deleted.");
		}

		$this->application->redirect(Url::site('/pages'));
	}

	public function lastSearch()
	{
		$search = $this->session->get('page.lastSearch', false);

		if ($search === false)
		{
			return '/pages';
		}

		return "/pages/search?q=$search";
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