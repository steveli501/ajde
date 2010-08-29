<?php 

class Ajde_Template_Parser_Phtml_Helper extends Ajde_Object_Singleton
{
	/**
	 * @return Ajde_Template_Parser_Helper
	 */
	public static function getInstance()
	{
		static $instance;
		return $instance === null ? $instance = new self : $instance;
	}
	
	/**
	 *
	 * @param string $name
	 * @param string $version
	 * @return void 
	 */
	public function requireJsLibrary($name, $version)
	{
		return Ajde_Component_Js::processStatic(array('library' => $name, 'version' => $version));
	}

	/**
	 *
	 * @param string $route
	 * @return string
	 */
	public function includeModule($route)
	{
		return Ajde_Component_Include::processStatic(array('route' => $route));
	}
}