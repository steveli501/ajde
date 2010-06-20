<?php

class Ajde_Core_App extends Ajde_Object_Singleton
{
	/**
	 *
	 * @return Ajde_Core_App
	 */
	public static function app()
	{
		return self::getInstance();
	}

	/**
	 *
	 * @staticvar Ajde_Core_App $instance
	 * @return Ajde_Core_App
	 */
	public static function getInstance()
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

		// Bootstrap init
		require_once(PRIVATE_DIR.CLASS_DIR."Ajde/Core/Bootstrap.php");
		$bootstrap = new Ajde_Core_Bootstrap();
		$bootstrap->run();

		$request = Ajde_Http_Request::fromGlobal();
		$document = Ajde_Document::fromRequest($request);
	}
}