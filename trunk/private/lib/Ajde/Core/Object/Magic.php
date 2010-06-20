<?php

abstract class Ajde_Core_Object_Magic extends Ajde_Core_Object
{
	protected $_data = array();
	
	public final function __call($method, $arguments)
	{
		$prefix = strtolower(substr($method, 0, 3));
		$key = substr($method, 3);
		$key = strtolower(substr($key, 0, 1)).substr($key, 1);
		switch ($prefix)
		{
			case "get":
				if ($this->has($key))
				{
					return $this->get($key);
				}
				else
				{
					throw new Ajde_Exception("Parameter '$key' not set in class ".get_class($this)." when calling get('$key')", 90007);
				}
			case "set":
				return $this->set($key, $arguments[0]);
			case "has":
				return $this->has($key);
		}
		throw new Ajde_Exception("Call to undefined method ".get_class($this)."::$method()", 90006);
    }


	/**
	 *
	 * @param mixed $name
	 * @param mixed $value
	 * @return mixed
	 */
	public final function set($key, $value)
	{
		$this->_data[$key] = $value;
		return true;
	}

	public final function get($key)
	{
		if ($this->has($key))
		{
			return $this->_data[$key];
		}
		else
		{
			throw new Ajde_Exception("Parameter '$key' not set in class ".get_class($this)." when calling get('$key')", 90007);
		}
	}

	public final function has($key)
	{
		return !empty($this->_data[$key]);
	}
}