<?php

namespace app\classes;

class Autoloader
{
	const CONTROLLERS_DIRECTORY = '/application/controllers';
	const CLASSES_DIRECTORY = '/application/classes';
	const MODELS_DIRECTORY = '/application/models';
	const COMPONENTS_DIRECTORY = '/application/components';
	const CORE_DIRECTORY = '/application/core';

	public function __construct()
	{
		$this->loadClasses();
		$this->loadCore();
		$this->loadControllers();
		$this->loadModels();
	}

	private function loadClasses()
	{
		$classes = $this->getFileList( $_SERVER['DOCUMENT_ROOT'].$this::CLASSES_DIRECTORY , true, array('Autoloader.php'));
		foreach($classes as $class)
		{
			include_once( $_SERVER['DOCUMENT_ROOT'].$this::CLASSES_DIRECTORY.'/'.$class );
		}
	}

	private function loadControllers()
	{
		$controllers = $this->getFileList( $_SERVER['DOCUMENT_ROOT'].$this::CONTROLLERS_DIRECTORY );
		foreach($controllers as $controller)
		{
			include_once ( $_SERVER['DOCUMENT_ROOT'].$this::CONTROLLERS_DIRECTORY.'/'.$controller );
		}

	}

	private function loadModels()
	{
		$models = $this->getFileList( $_SERVER['DOCUMENT_ROOT'].$this::MODELS_DIRECTORY );
		foreach($models as $model)
		{
			include_once ( $_SERVER['DOCUMENT_ROOT'].$this::MODELS_DIRECTORY.'/'.$model );
		}
	}

	private function loadCore()
	{
		$cores = $this->getFileList($_SERVER['DOCUMENT_ROOT'].$this::CORE_DIRECTORY, true, array('Core.php'));
		foreach($cores as $core)
		{
			include_once ( $_SERVER['DOCUMENT_ROOT'].$this::CORE_DIRECTORY.'/'.$core );
		}
	}

	public function getFileList($directory, $showExtension = true, $exclude = array())
	{
		$exclude[] = ".";
		$exclude[] = "..";
		$dir = dir($directory);

		$list = '';

		while ($entry = $dir->read())
		{
			$type = filetype($directory.'/'.$entry);
			if(!in_array($entry, $exclude) && $type != 'dir')
			{
				$info = pathinfo($entry);
				$filename = basename($entry,'.'.$info['extension']);
				$list[] = $showExtension ? $entry : $filename;
			}
		}
		$dir->close();

		return $list;
	}
}