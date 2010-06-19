<?php

abstract class Ajde_Core_Object_Singleton extends Ajde_Core_Object
{
	protected static $__pattern = self::OBJECT_PATTERN_SINGLETON;

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
    abstract public static function getInstance();

    // Do not allow an explicit call of the constructor
    final protected function __construct() {}

    // Do not allow the clone operation:
    final protected function __clone() {}
	
}