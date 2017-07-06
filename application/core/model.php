<?php

namespace app\core;

use app\base\Base;
use app\components\library\ServiceLocator;
use app\components\library\ServiceLocatorInterface;

class Model extends Base
{
	public function get_data()
	{
	}

	/**
	 * @return \app\components\library\ServiceLocator
	 */
	protected static function getLocator()
	{
		$locator = ServiceLocator::i();

		return $locator;
	}

	/**
	 * @return \app\components\services\DataBase
	 */
	protected static function getDatabase()
	{
		$database = self::getLocator()->get('database');

		return $database;
	}
}