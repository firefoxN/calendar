<?php

namespace app\components\library;

interface ServiceLocatorInterface {
	public function set($name, $service);
	public function get($name);
	public function has($name);
	public function remove($name);
	public function clear();
}