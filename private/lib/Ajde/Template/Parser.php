<?php 

class Ajde_Template_Parser extends Ajde_Object_Standard
{
	/**
	 * 
	 * @var Ajde_Template
	 */
	protected $_template = null;
	
	/**
	 * 
	 * @param Ajde_Template $template
	 */
	public function __construct(Ajde_Template $template)
	{
		$this->_template = $template;
	}
	
	/**
	 * 
	 * @return Ajde_Template
	 */
	public function getTemplate()
	{
		return $this->_template;
	}
	
	public function parse()
	{
		return $this->_getContents();
	}
	
	protected function _getContents()
	{
		ob_start();
		include $this->getTemplate()->getFullPath();
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}