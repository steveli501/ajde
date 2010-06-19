<?php

Class Config {

	// Redirect this class to the following config stage
	public static $stage			= "dev";

	/**
	 * 
	 * @return Config_Base 
	 */
	public static function getInstance() {
		static $instance = null;
		if (empty($instance)) {
			$className = "Config_".ucfirst(self::$stage);
			$instance = new $className();
		}
		return $instance;
	}

	/**
	 * 
	 * @param string $param
	 * @return mixed
	 */
	public static function get($param) {
		$instance = self::getInstance();
		if (isset($instance->$param)) {
			return $instance->$param;
		} else {
			throw new Ajde_Exception("Config parameter $param not set");
		}
	}

}