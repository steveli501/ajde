<?php

abstract class Ajde_Template extends Ajde_Object_Standard
{
	public $_contents = null;
	
	public function  __construct($base, $action, $format = 'html')
	{
		// TODO: refactor into factory
		$this->setBase($base);
		$this->setAction($action);
		$this->setFormat($format);
		// Check for Ajde_Template_Xhtml template
		if ($this->isXhtml()) {
			return new Ajde_Template_Xhtml($base, $action);
		}
		$this->exist();
	}

	public function exist()
	{
		if (!$this->setFilename())
		{
			$exception = new Ajde_Exception(sprintf("Template file %s or %s not
					found",
					$this->_getSpecificFormatFilename(),
					$this->_getDefaultFormatFilename()), 90010);
			Ajde::routingError($exception);
		}
	}
	
	public function isXhtml()
	{
		if ($this->getFormat() != 'html') {
			return false;
		}
		$xhtmlFilename = $this->_getXhtmlFilename();
		return file_exists($xhtmlFilename);
	}

	public function setFilename()
	{
		$specificFormatFilename = $this->_getSpecificFormatFilename();
		if (file_exists($specificFormatFilename))
		{
			$this->set("filename", $specificFormatFilename);
			return true;
		}
		$defaultFormatFilename = $this->_getDefaultFormatFilename();
		if (file_exists($defaultFormatFilename))
		{
			$this->set("filename", $defaultFormatFilename);
			return true;
		}
		if ($this->getFormat() == 'html') {
			$phtmlFilename = $this->_getPhtmlFilename();
			if (file_exists($phtmlFilename))
			{
				$this->set("filename", $phtmlFilename);
				return true;
			}
		}
		return false;
	}

	protected function _getSpecificFormatFilename()
	{
		return $this->getBase() . 'template/' . $this->getAction() . '.' . $this->getFormat() . '.php';
	}

	protected function _getDefaultFormatFilename()
	{
		return $this->getBase() . 'template/' . $this->getAction() . '.default.php';
	}
	
	protected function _getPhtmlFilename()
	{
		return $this->getBase() . 'template/' . $this->getAction() . '.phtml';
	}
	
	protected function _getXhtmlFilename()
	{
		return $this->getBase() . 'template/' . $this->getAction() . '.xhtml';
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

		Ajde_Cache::getInstance()->addFile($this->getFilename());
		include $this->getFilename();
		
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