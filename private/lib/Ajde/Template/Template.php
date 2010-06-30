<?php

class Ajde_Template
{
	public static function getFilename($module, $action, $format)
	{
		return PRIVATE_DIR.APP_DIR.$module.'/templates/'.$action.'.'.$format.'.php';
	}
}