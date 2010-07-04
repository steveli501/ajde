<?php

class Ajde_Core_App_Template extends Ajde_Template
{
	/**
	 *
	 * @param Ajde_Http_Request $request
	 * @return Ajde_Core_App_Template
	 */
	public static function fromRequest(Ajde_Http_Request $request)
	{
		$base = PRIVATE_DIR.APP_DIR.$request->getModule() . '/';
		$action = $request->getAction();
		$format = $request->getFormat();
		return new self($base, $action, $format);
	}
}