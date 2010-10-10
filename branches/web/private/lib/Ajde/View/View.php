<?php

class Ajde_View extends Ajde_Template
{	
	/**
	 * 
	 * @param Ajde_Controller $controller
	 * @return Ajde_View
	 */
	public static function fromController(Ajde_Controller $controller) {
		$base = PRIVATE_DIR.APP_DIR.MODULE_DIR. $controller->getModule() . '/'; 
		$action = $controller->getAction();
		$format = $controller->hasFormat() ? $controller->getFormat() : 'html';				
		return new self($base, $action, $format);
	}

	/**
	 *
	 * @param Ajde_Core_Route $route
	 * @return Ajde_View
	 */
	public static function fromRoute(Ajde_Core_Route $route)
	{
		throw new Ajde_Core_Exception_Deprecated();
		$base = PRIVATE_DIR.APP_DIR.MODULE_DIR. $route->getModule() . '/';
		$action = $route->getAction();
		$format = $route->getFormat();
		return new self($base, $action, $format);
	}
}