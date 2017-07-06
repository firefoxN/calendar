<?php

namespace app\classes;

abstract class singleton
{
	private static $_aInstances = array();
	public static function getInstance()
	{
		$sClassName = get_called_class();
		if( class_exists($sClassName) )
		{
			if( !isset( self::$_aInstances[ $sClassName ] ) )
				self::$_aInstances[ $sClassName ] = new $sClassName();
			return self::$_aInstances[ $sClassName ];
		}
		return 0;
	}

	public static function i()
	{
		return self::getInstance();
	}

	final private function __clone(){}
	private function __construct(){}
}