<?php

class Ajde_Core_App
{
	/**
	 *
	 * @staticvar Ajde_Core_App $instance
	 * @return Ajde_Core_App
	 */
	public static function app()
	{
		static $instance;
		return $instance === null ? $instance = new self : $instance;
	}

	/**
	 *
	 * @return Ajde_Core_App
	 */
	public static function create()
	{
		return self::app();
	}

	public function run()
	{
		$app = self::app();

		// Autoloader init
		$app->_configureAutoloader();
		
		// Bootstrap init
		require_once(PRIVATE_DIR.CLASS_DIR."Ajde/Core/Bootstrap.php");
		$bootstrap = new Ajde_Core_Bootstrap();
		$bootstrap->run();
	}

	private function _configureAutoloader() {
		require_once(PRIVATE_DIR.CLASS_DIR."Ajde/Core/Autoloader.php");
		Ajde_Core_Autoloader::register();
	}
}