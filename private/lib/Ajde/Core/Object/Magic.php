<?php

abstract class Ajde_Core_Object_Magic extends Ajde_Core_Object
{
	protected $_data = array();
	
	public function __call($method, $arguments)
	{
		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $arguments);
		}

		$prefix = strtolower(substr($method, 0, 3));
		$key = substr($method, 3);
		$key = strtolower(substr($key, 0, 1)).substr($key, 1);

		if (empty($prefix) || empty($key))
		{
			throw new Ajde_Exception("Method $method on class ".get_class($this)." does not exist (no getter/setter)");
		}

		if ($prefix == "get" && isset($this->_data[$key]))
		{
			return $this->get($key);
		}
		elseif ($prefix == "get" && !isset($this->_data[$key]))
		{
			throw new Ajde_Exception("$key not set in class ".get_class($this)." when calling $method");
			return false;
		}

		if ($prefix == "set")
		{
			return $this->set($key, $arguments[0]);
		}

		if ($prefix == "has")
		{
			return $this->has($key);
		}

		throw new Ajde_Exception("Method $method on class ".get_class($this)." does not exist (wrong prefix)");
    }


	/**
	 *
	 * @param mixed $name
	 * @param mixed $value
	 * @return mixed
	 */
	function set($key, $value)
	{
		$this->_data[$key] = $value;
		return true;
	}

	function get($key)
	{
		return $this->_data[$key];
	}

	function has($key)
	{
		return !empty($this->_data[$key]);
	}

	function hasData()
	{
		return !empty($this->_data);
	}
}