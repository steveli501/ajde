<?php

class Ajde_Template_Parser_Phtml extends Ajde_Template_Parser
{
	public static function getInstance()
	{
		static $instance;
		return $instance === null ? $instance = new self : $instance;
	}
	
	/**
	 * HELPER FUNCTIONS
	 */

	/**
	 *
	 * @param string $name
	 * @param string $version
	 */
	public function requireJsLibrary($name, $version)
	{
		$url = Ajde_Resource_JsLibrary::getUrl($name, $version);
		$resource = new Ajde_Resource_Remote(Ajde_Resource::TYPE_JAVASCRIPT, $url);
		Ajde::app()->getDocument()->addResource($resource, Ajde_Document_Format_Html::RESOURCE_POSITION_FIRST);
	}

	/**
	 *
	 * @param string $route
	 */
	public function includeModule($route)
	{
		echo $this->getModule($route)->invoke();
	}

	/**
	 *
	 * @param string $route
	 * @return Ajde_Controller
	 */
	public function getModule($route)
	{
		return Ajde_Controller::fromRoute(new Ajde_Core_Route($route));
	}
}