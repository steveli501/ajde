<?php 

class Ajde_Template_Parser extends Ajde_Object_Standard
{
	/**
	 * 
	 * @return Ajde_Template_Parser
	 */
	public static function getInstance()
	{
		static $instance;
		return $instance === null ? $instance = new self : $instance;
	}
	
	public function parse(Ajde_Template $template)
	{
		return $this->_getContents($template->getFullPath());
	}
	
	protected function _getContents($fullPath)
	{
		ob_start();
		include $fullPath;
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}