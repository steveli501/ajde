<?php

class Ajde_Template_Parser_Phtml extends Ajde_Template_Parser
{
	/**
	 * 
	 * @return Ajde_Template_Parser_Phtml
	 */
	public static function getInstance()
	{
		static $instance;
		return $instance === null ? $instance = new self : $instance;
	}
	
	/**
	 * 
	 * @return Ajde_Template_Parser_Helper
	 */
	public function getHelper()
	{
		return Ajde_Template_Parser_Phtml_Helper::getInstance();
	}
	
	public function __fallback($method, $arguments)
	{
		$helper = $this->getHelper();
		if (method_exists($helper, $method)) {
			return call_user_func_array(array($helper, $method), $arguments);
		} else {
			throw new Ajde_Exception("Call to undefined method ".get_class($this)."::$method()", 90006);
		}
    }
}