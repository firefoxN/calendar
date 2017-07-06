<?php

namespace app\base;

use app\components\library\ServiceLocatorInterface;

class Base {
	public $locator;

	public function __construct(ServiceLocatorInterface $locator)
	{
		$this->locator = $locator;
	}
}