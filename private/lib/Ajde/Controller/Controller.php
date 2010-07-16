<?php

class Ajde_Controller extends Ajde_Object_Standard
{
	/**
	 *
	 * @param Ajde_Request $request
	 * @return Ajde_Controller
	 */
	public static function fromRequest(Ajde_Http_Request $request)
	{
		$module = $request->getParam("module");
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
			$request = Ajde::app()->getRequest();
		}
		$action = isset($action) ? $action : $request->getAction();
		$format = isset($format) ? $format : $request->getFormat();
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
						$request->getAction(),
						$request->getModule()
					), 90011);
			Ajde::routingError($exception);
		}
		return $this->$actionFunction();

	}

	public function loadTemplate()
	{
		$request = Ajde::app()->getRequest();
		$template = Ajde_Core_App_Template::fromRequest($request);
		return $template->getContents();
	}

	public function redirect()
	{
		
	}
}