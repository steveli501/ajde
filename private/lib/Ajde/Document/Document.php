<?php

class Ajde_Document extends Ajde_Core_Object_Standard
{
	protected $_request;
	
	public function  __construct()
	{

	}

	/**
	 *
	 * @param Ajde_Http_Request $request
	 * @return Ajde_Document
	 */
	public static function fromRequest(Ajde_Http_Request $request)
	{
		$instance = new self();
		$instance->setRequest($request);
		return $instance;
	}

	/**
	 *
	 * @return Ajde_Http_Request
	 */
	public function getRequest()
	{
		return $this->_request;
	}

	/**
	 *
	 * @param Ajde_Http_Request $request 
	 */
	public function setRequest(Ajde_Http_Request $request)
	{
		$this->_request = $request;
	}
}