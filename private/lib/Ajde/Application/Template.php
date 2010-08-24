<?php

class Ajde_Application_Template extends Ajde_Template
{	
	public static function factory($module, $action, $format = 'html') {
		$base = PRIVATE_DIR.APP_DIR.MODULE_DIR.$module . '/';
		parent::__construct($base, $action, $format);
	}

	/**
	 *
	 * @param Ajde_Core_Route $route
	 * @return Ajde_Application_Template
	 */
	public static function fromRoute(Ajde_Core_Route $route)
	{
		$base = PRIVATE_DIR.APP_DIR.$route->getModule() . '/';
		$action = $route->getAction();
		$format = $route->getFormat();
		return new self($base, $action, $format);
	}
}