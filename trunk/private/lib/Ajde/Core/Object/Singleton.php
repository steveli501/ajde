<?php

interface Ajde_Core_Object_Singleton_Interface
{
	/**
	 * Example:
	 *
	 * public static function getInstance()
	 * {
     *    static $instance;
     *    return $instance === null ? $instance = new self : $instance;
	 * }
	 *
	 * @abstract
	 */
    public static function getInstance();
}

abstract class Ajde_Core_Object_Singleton extends Ajde_Core_Object
implements Ajde_Core_Object_Singleton_Interface
{
	protected static $__pattern = self::OBJECT_PATTERN_SINGLETON;

	public static function __getPattern()
	{
		return self::$__pattern;
	}

	// Do not allow an explicit call of the constructor
    protected final function __construct() {}

	// Do not allow the clone operation
    private final function __clone() {}
}

