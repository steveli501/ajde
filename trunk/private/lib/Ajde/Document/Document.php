<?php

class Ajde_Document extends Ajde_Object_Standard
{
	/**
	 *
	 * @param Ajde_Http_Request $request 
	 */
	public function __construct()
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

	public function render($contents)
	{
		echo $contents;
	}
}