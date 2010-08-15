<?php

class Ajde_Core_App_Template extends Ajde_Template
{
	public function  __construct($module, $action, $format = 'html')
	{
		$base = PRIVATE_DIR.APP_DIR.$module . '/';
		parent::__construct($base, $action, $format);
	}

	/**
	 *
	 * @param Ajde_Core_Route $route
	 * @return Ajde_Core_App_Template
	 */
	public static function fromRoute(Ajde_Core_Route $route)
	{
		$base = PRIVATE_DIR.APP_DIR.$route->getModule() . '/';
		$action = $route->getAction();
		$format = $route->getFormat();
		return new self($base, $action, $format);
	}
}