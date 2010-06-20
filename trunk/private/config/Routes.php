<?php

Class Routes {

	protected static $routes = array(
		"home" => "home/home",
	);

	public static function getAll()
	{
		return self::$routes;
	}

}