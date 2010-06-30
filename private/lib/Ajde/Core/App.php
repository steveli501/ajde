<?php

class Ajde_Core_App extends Ajde_Object_Singleton
{
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
	public static function app()
	{
		return self::getInstance();
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
		$bootstrap = new Ajde_Core_Bootstrap();
		$bootstrap->run();

		$request = Ajde_Http_Request::fromGlobal();
		var_dump($request);
		$document = Ajde_Document::fromRequest($request);
	}
}