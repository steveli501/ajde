<?php

abstract class Ajde_Core_Object_Standard extends Ajde_Core_Object_Magic
{
	protected static $__pattern = self::OBJECT_PATTERN_STANDARD;

	public static function __getPattern()
	{
		return self::$__pattern;
	}
}