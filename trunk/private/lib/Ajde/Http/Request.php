<?php

class Ajde_Http_Request extends Ajde_Core_Object_Standard
{
	public static function fromGlobal()
	{
		$instance = new self();
		foreach($_GET as $key => $value)
		{
			$instance->set($key, $value);
		}
		$defaults = Config::get("defaultRoute");
		foreach(array_keys($defaults) as $key)
		{
			if (!$instance->has($key)) {
				$instance->set($key, $defaults[$key]);
			}
		}
		return $instance;
	}

	public function getParam($key, $default = null) {
		return $this->has($key) ? $this->get($key) : $default;
	}

}