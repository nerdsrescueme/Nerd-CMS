<?php

namespace Application\Tasks;

class Printer extends \Geek\Design\Task
{
	public function run()
	{
		$this->geek->write('The print task has been run!!!');
	}

	/**
	 * Help
	 *
	 * Usage: php ion help
	 *
	 * @return   boolean
	 */
	public function help()
	{
		return <<<HELP

The print task

HELP;
	}
}