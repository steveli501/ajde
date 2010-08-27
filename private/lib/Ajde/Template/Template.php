<?php

class Ajde_Template extends Ajde_Object_Standard
{
	public $_contents = null;
	
	public function  __construct($base, $action, $format = 'html')
	{
		$this->setBase($base);
		$this->setAction($action);
		$this->setFormat($format);
	}

	public function exist()
	{
		return file_exists($this->getFullPath());
	}
	
	public function setFilename($filename)
	{
		$this->set('filename', $filename);
		$this->setFullPath();
		if (!$this->exist()) {
			$exception = new Ajde_Exception(sprintf("Template file %s not found",
					$this->getFullPath()), 90010);
			Ajde::routingError($exception);
		}
	}
	
	public function setFullPath()
	{
		$fullPath = $this->getBase() . TEMPLATE_DIR . $this->getFilename();
		return $this->set("fullPath", $fullPath);
	}
	
	public function getFullPath()
	{
		return $this->get("fullPath");
	}

	public function getFilename()
	{
		return $this->get("filename");
	}

	public function getBase()
	{
		return $this->get('base');
	}

	public function getAction()
	{
		return $this->get('action');
	}

	public function getFormat()
	{
		return $this->get('format');
	}

	public function getContents()
	{
		if (!isset($this->_contents))
		{
			Ajde_Event::trigger($this, 'beforeGetContents');
			$this->setContents($this->includeContents());
			Ajde_Event::trigger($this, 'afterGetContents');
		}
		return $this->_contents;
	}

	public function setContents($contents)
	{
		$this->_contents = $contents;
	}

	public function includeContents()
	{
		ob_start();

		Ajde_Cache::getInstance()->addFile($this->getFullPath());
		include $this->getFullPath();
		
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	/**
	 * HELPER FUNCTIONS
	 */

	/**
	 *
	 * @param string $name
	 * @param string $version
	 */
	public function requireJsLibrary($name, $version)
	{
		$url = Ajde_Resource_JsLibrary::getUrl($name, $version);
		$resource = new Ajde_Resource_Remote(Ajde_Resource::TYPE_JAVASCRIPT, $url);
		Ajde::app()->getDocument()->addResource($resource, Ajde_Document_Format_Html::RESOURCE_POSITION_FIRST);
	}

	/**
	 *
	 * @param string $route
	 */
	public function includeModule($route)
	{
		echo $this->getModule($route)->invoke();
	}

	/**
	 *
	 * @param string $route
	 * @return Ajde_Controller
	 */
	public function getModule($route)
	{
		return Ajde_Controller::fromRoute(new Ajde_Core_Route($route));
	}
}