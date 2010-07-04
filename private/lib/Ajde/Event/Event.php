<?php

class Ajde_Event extends Ajde_Object_Static
{
	protected static $eventStack = array();

	public static function register($object, $event, $callback)
	{
		self::$eventStack[self::className($object)][$event][] = $callback;
	}

	public static function trigger($object, $event)
	{
		foreach(self::$eventStack as $className => $eventStack)
		{
			if (self::className($object) == $className ||
					is_subclass_of(self::className($object), $className))
			{
				if (isset($eventStack[$event])) {
					foreach($eventStack[$event] as $eventCallback)
					{
						$callback = null;
						if (is_callable($eventCallback))
						{
							$callback = $eventCallback;
						}
						elseif (is_string($eventCallback))
						{
							if (is_callable(array($object, $eventCallback))) {
								$callback = array($object, $eventCallback);
							}
						}
						if (isset($callback))
						{
							call_user_func($callback);
						}
						else
						{
							// TODO: right now never fires in Object_Magic objects
							// because of the __call magic function. Workaround
							// could be something like in_array("bar",get_class_methods($f1)
							// see: http://php.net/manual/en/function.method-exists.php
							throw new Ajde_Exception('Callback is not valid');
						}
					}
				}
			}
		}
	}

	protected static function className($object)
	{
		if (is_object($object))
		{
			return get_class($object);
		}
		elseif (is_string($object))
		{
			return $object;
		}
		throw new Ajde_Exception('No classname or object instance given');
	}
}