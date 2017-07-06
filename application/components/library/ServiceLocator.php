<?php

namespace app\components\library;

use app\classes\singleton;

class ServiceLocator extends singleton implements ServiceLocatorInterface
{
	protected $services = array();

	public function set($name, $service)
	{
		if (!is_object($service)) {
			throw new \InvalidArgumentException(
				"Only objects can be registered with the locator.");
		}
		if (!in_array($service, $this->services, true)) {
			$this->services[$name] = $service;
		}
		return $this;
	}

	public function get($name)
	{
		if (!isset($this->services[$name])) {
			throw new \RuntimeException(
				"The service $name has not been registered with the locator.");
		}
		return $this->services[$name];
	}

	public function has($name)
	{
		return isset($this->services[$name]);
	}

	public function remove($name)
	{
		if (isset($this->services[$name])) {
			unset($this->services[$name]);
		}
		return $this;
	}

	public function clear()
	{
		$this->services = array();
		return $this;
	}
}