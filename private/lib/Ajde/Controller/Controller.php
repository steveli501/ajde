<?php

class Ajde_Controller extends Ajde_Object_Standard
{
	/**
	 *
	 * @param Ajde_Core_Route $route
	 * @return Ajde_Controller
	 */
	public static function fromRoute(Ajde_Core_Route $route)
	{
		$module = $route->getModule();
		if (!Ajde_Core_Autoloader::exists($module)) {
			$exception = new Ajde_Exception("Controller for module $module not found",
					90008);
			Ajde::routingError($exception);
		}
		return new $module();
	}

	public function invoke($action = null, $format = null)
	{
		if (!isset($action) || !isset($format)) {
			$route = Ajde::app()->getRoute();
		}
		$action = isset($action) ? $action : $route->getAction();
		$format = isset($format) ? $format : $route->getFormat();
		$defaultFunction = $action . "Default";
		$formatFunction = $action . ucfirst($format);
		if (method_exists($this, $formatFunction))
		{
			$actionFunction = $formatFunction;
		}
		elseif (method_exists($this, $defaultFunction))
		{
			$actionFunction = $defaultFunction;
		}
		else
		{
			$exception = new Ajde_Exception(sprintf("Action %s for module %s not found",
						$route->getAction(),
						$route->getModule()
					), 90011);
			Ajde::routingError($exception);
		}
		return $this->$actionFunction();

	}

	public function loadTemplate()
	{
		$route = Ajde::app()->getRoute();
		$template = Ajde_Core_App_Template::fromRoute($route);
		return $template->getContents();
	}

	public function redirect()
	{
		
	}
}