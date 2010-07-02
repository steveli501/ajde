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
		$filename = PRIVATE_DIR.APP_DIR.$request->getModule().'/templates/'.$request->getAction().'.'.$request->getFormat().'.php';
		return new self($filename);
	}
}