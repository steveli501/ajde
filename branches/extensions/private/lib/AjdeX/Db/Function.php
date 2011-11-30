<?php

class AjdeX_Db_Function
{
	private $_function = null;
	
	public function __construct($functionName)
	{
		$this->_function = $functionName;
	}
	
	public function __toString()
	{
		return $this->_function;
	}
}