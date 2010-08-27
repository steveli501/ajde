<?php 

class Ajde_Template_Parser extends Ajde_Object_Singleton
{
	public static function getInstance()
	{
		static $instance;
		return $instance === null ? $instance = new self : $instance;
	}
	
	public function parse(Ajde_Template $template)
	{
		ob_start();
		include $template->getFullPath();
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}