<?php

abstract class Ajde_Document extends Ajde_Object_Standard
{
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
		$format = $request->getParam("format");
		$documentClass = "Ajde_Document_Format_" . ucfirst($format);
		if (!Ajde_Core_Autoloader::exists($documentClass)) {
			$exception = new Ajde_Exception("Document format $format not found",
					90009);
			Ajde::routingError($exception);
		}
		return new $documentClass();
	}

	/**
	 * @return Ajde_Layout
	 */
	public function getLayout() {
		return $this->get("layout");
	}

	/**
	 *
	 * @param string $contents
	 */
	public function setBody($contents)
	{
		$this->set('body', $contents);
	}

	/**
	 *
	 * @return string
	 */
	public function getBody()
	{
		return $this->get('body');
	}

	public function render()
	{
		$contents = $this->getLayout()->getContents();
		Ajde::app()->getResponse()->setData($contents);
	}

	/**
	 *
	 * @param Ajde_Template_Resource $resource
	 */
	abstract public function addResource(Ajde_Template_Resource $resource);

	abstract public function getResourceTypes();

}