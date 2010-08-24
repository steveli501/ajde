<?php

class Ajde_Template_Xhtml extends Ajde_Template
{
	public function  __construct($base, $action)
	{
		$this->setBase($base);
		$this->setAction($action);
		$this->setFormat('xhtml');
		$this->exist();
	}
	
	public function exist()
	{
		if (!$this->setFilename())
		{
			$exception = new Ajde_Exception(sprintf("Template file %s not
					found",
					$this->_getXhtmlFilename()), 90010);
			Ajde::routingError($exception);
		}
	}
	
	public function isXhtml()
	{
		return true; 
	}

	public function setFilename()
	{
		$xhtmlFilename = $this->_getXhtmlFilename();
		if (file_exists($xhtmlFilename))
		{
			$this->set("filename", $xhtmlFilename);
			return true;
		}
		return false;
	}
}