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

	public function invoke()
	{
		$request = Ajde::app()->getRequest();
		$action = $request->getAction();
		$format = $request->getFormat();
		$defaultFunction = $action . "Default";
		$formatFunction = $action . ucfirst($action);
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
		$filename = Ajde_Template::getFilename(
				$request->getModule(),
				$request->getAction(),
				$request->getFormat()
		);
		if (!file_exists($filename)) {
			$exception = new Ajde_Exception(sprintf("Template for module %s with
					action %s not found",
						$request->getModule(),
						$request->getAction()
					), 90010);
			Ajde::routingError($exception);
		}

		ob_start();
		include $filename;
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	public function redirect()
	{
		
	}
}