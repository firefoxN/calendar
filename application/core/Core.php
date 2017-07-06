<?php

namespace app\core;

use app\classes\Autoloader;
use app\components\library\ServiceLocatorInterface;

class Core
{
	private $autoLoader;
	private $locator;

	public function __construct(ServiceLocatorInterface $locator)
	{
		$this->autoLoader = $this->getAutoloader();
		$this->locator = $locator;
	}

	private function getAutoloader()
	{
		return new Autoloader();
	}

	public function start()
	{
		route::i()->start($this->locator);
	}
}