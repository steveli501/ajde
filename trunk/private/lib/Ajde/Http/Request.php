<?php

class Ajde_Http_Request extends Ajde_Object_Standard
{
	/**
	 * @return Ajde_Http_Request
	 */
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

	public static function getRefferer() {
		return $_SERVER['HTTP_REFERER'];
	}

	public function getParam($key, $default = null) {
		return $this->has($key) ? $this->get($key) : $default;
	}

	public function getModule($default = null) {
		return $this->getParam("module", $default);
	}

	public function getAction($default = null) {
		return $this->getParam("action", $default);
	}

	public function getFormat($default = null) {
		return $this->getParam("format", $default);
	}

}