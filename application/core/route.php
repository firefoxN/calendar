<?php

namespace app\core;

use app\classes\singleton;
use app\components\library\ServiceLocatorInterface;

class Route extends singleton
{
	const DEFAULT_CONTROLLER = 'Main';
	const DEFAULT_ACTION = 'Index';

	public static function start(ServiceLocatorInterface $locator)
	{
		$controllerName = self::DEFAULT_CONTROLLER;
		$actionName = self::DEFAULT_ACTION;

		$arrUrl = explode('?', $_SERVER['REQUEST_URI']);
		$urlString = $arrUrl[0];

		if ($urlString != '/' && $urlString != '') {
			try {
				$url_path = parse_url($urlString, PHP_URL_PATH);
				$uri_parts = explode('/', trim($url_path, ' /'));
				$controllerName = array_shift($uri_parts);
				$action = array_shift($uri_parts);
				$actionName = $action ? : self::DEFAULT_ACTION;
			} catch (\Exception $e) {
				Route::ErrorPage404();
			}
		}

		// add prefics
		$controllerName = 'Controller' . ucfirst($controllerName);
		$actionName = 'action' . ucfirst($actionName);

		// create controller
		$controller_class_name = '\\app\controllers\\'.$controllerName;
		$class = new \ReflectionClass($controller_class_name);

		if ($class->getConstructor() === NULL) {

			$controller = new $controller_class_name();
//			$controller = new $controllerName();
		} else {
			$controller = $class->newInstanceArgs(array($locator));
		}
		//$controller = new $controllerName;
		$action = $actionName;

		if (method_exists($controller, $action)) {
			$controller->$action();
		} else {
			Route::ErrorPage404();
		}

	}

	public static function ErrorPage404()
	{
		$host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:' . $host . '404');
	}
}