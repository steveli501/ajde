<?php

abstract class Ajde_Core_Object
{
	const OBJECT_PATTERN_UNDEFINED		= 0;
	const OBJECT_PATTERN_STANDARD		= 1;
	const OBJECT_PATTERN_SINGLETON		= 2;
	const OBJECT_PATTERN_STATIC			= 3;

	protected static $__pattern = self::OBJECT_MODE_UNDEFINED;

	public static function __getPattern()
	{
		$caller = get_called_class();
		return $caller::$__pattern;
	}

}