<?php

class Ajde_Template_Factory extends Ajde_Object_Static
{	
	public static function fromController(Ajde_Controller $controller) {
		$module = $controller->getModule();
		$action = $controller->getAction();
		$format = $controller->hasFormat() ? $controller->getFormat() : 'html';
		$base = PRIVATE_DIR.APP_DIR.MODULE_DIR.$module . '/';
		
		// go see what is available
		$fileNamePatterns = array(
			$action . '.' . $format, $action
		);
		$fileTypes = array(
			'phtml' => 'Phtml', 'xhtml' => 'Xhtml'
		);
		$fileName = null;
		foreach($fileNamePatterns as $fileNamePattern) {
			foreach($fileTypes as $fileType => $parser) {
				$filePattern = $fileNamePattern . '.' . $fileType;
				if ($_fileName = Ajde_FS_Find::findFile($base.TEMPLATE_DIR, $filePattern)) {
					$fileName = $_fileName;
					$className = $_className;
					break(2);
				}				
			}
		}
		if (!isset($fileName)) {
			$exception = new Ajde_Exception(sprintf("Template file for module %s,
					action %s, format %s not found",
					$module, $action, $format), 90010);
			Ajde::routingError($exception);
		}
		
		/* @var $template Ajde_Template */
		$template = new Ajde_Template($base, $action, $format);
		$template->setFilename($fileName);
		return $template;
	}

	/**
	 *
	 * @param Ajde_Core_Route $route
	 * @return Ajde_Application_Template
	 */
	public static function fromRoute(Ajde_Core_Route $route)
	{
		throw new Ajde_Core_Exception_Deprecated();
		$base = PRIVATE_DIR.APP_DIR.$route->getModule() . '/';
		$action = $route->getAction();
		$format = $route->getFormat();
		return new Ajde_Template($base, $action, $format);
	}
}