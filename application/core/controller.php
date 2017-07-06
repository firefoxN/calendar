<?php

namespace app\core;

use app\components\library\ServiceLocator;
use app\components\library\ServiceLocatorInterface;

class Controller
{
	public $model;
	public $view;
	protected $locator;

	function __construct(ServiceLocatorInterface $locator)
	{
		$this->view = new View();
		$this->locator = $locator;
	}

	public function actionIndex()
	{
	}

	private function getLocator()
	{
		return $this->locator;
	}
}