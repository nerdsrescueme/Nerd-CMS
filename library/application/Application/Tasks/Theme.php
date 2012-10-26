<?php

namespace Application\Tasks;

class Theme extends \Geek\Design\Task
{
    public function run()
    {
        $this->geek->write('The theme task has been run!!!');
    }

	/**
	 * Install a theme
	 *
	 * This task installs a theme into the public/themes directory. The theme is
	 * cloned from a git repository into the themes directory... This enables us
	 * to allow themes to be shared, updated and essentially acted on in any way
	 * that git allows you to.
	 *
	 * # Usage
	 *
	 *     php geek application.theme.install https://github.com/user/repo.git {THEME_NAME}
	 *
	 * @return void
	 */
	public function install()
	{
		list($task, $repo, $folder) = $this->geek->args();

		$parts = explode($repo);
		$name  = str_replace('.git', '', end($parts));

		$folder  = join(DS, ['public', 'themes', $folder]);
		$command = "git clone $repo $folder";
		$return  = [];

		$this->geek->write('');
		$this->geek->write("Importing '$name' theme into $folder");

		exec($command, $return);

		foreach ($return as $r) {
			$this->geek->write($r);
		}

		$this->geek->write("Finished importing '$name' theme");
		$this->geek->write('');
	}

	/**
	 * Uninstall a theme
	 *
	 * This task uninstalls a theme.
	 *
	 * # Usage
	 *
	 *     php geek application.theme.uninstall {THEME_NAME}
	 *
	 * @return void
	 */
	public function uninstall()
	{
		list($task, $theme) = $this->geek->args();

		$folder  = join(DS, [\Nerd\DOCROOT, 'themes', $theme]);
		$command = "rm -rf $folder";
		$return  = [];

		$this->geek->write('');
		$this->geek->write("Removing $theme theme");

		exec($command, $return);

		foreach ($return as $r) {
			$this->geek->write($r);
		}

		$this->geek->write("Finished removing '$name' theme");
		$this->geek->write('');
	}

	/**
	 * Update a theme
	 *
	 * # Usage
	 *
	 *     php geek application.theme.update {THEME_NAME}
	 *
	 * @return void
	 */
	public function update()
	{
		list($task, $theme) = $this->geek->args();

		$folder  = join(DS, [\Nerd\DOCROOT, 'themes', $theme]);
		$command = "cd $folder & git pull";
		$return  = [];

		$this->geek->write('');
		$this->geek->write("Updating $theme theme");

		exec($command, $return);

		foreach ($return as $r) {
			$this->geek->write($r);
		}

		$this->geek->write("Finished updating $theme theme");
		$this->geek->write('');
	}

	/**
	 * HELP
	 *
	 * @return string
	 */
    public function help()
    {
        return <<<HELP

The print task

HELP;
    }
}
