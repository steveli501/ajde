<?php

abstract class Ajde_Template extends Ajde_Object_Standard
{
	public function  __construct($base, $action, $format = 'html')
	{
		$this->setBase($base);
		$this->setAction($action);
		$this->setFormat($format);
		$this->exist();
	}

	public function exist() {
		$filename = $this->getFilename();
		if (!file_exists($filename)) {
			$exception = new Ajde_Exception(sprintf("Template file %s not
					found", $filename), 90010);
			Ajde::routingError($exception);
		}
	}

	public function getFilename()
	{
		return $this->getBase() . 'template/' . $this->getAction() . '.' . $this->getFormat() . '.php';
	}

	public function getBase() {
		return $this->get('base');
	}

	public function getAction() {
		return $this->get('action');
	}

	public function getFormat() {
		return $this->get('format');
	}

	public function getContents() {
		Ajde_Event::trigger($this, 'beforeGetContents');
		ob_start();

		Ajde_Cache::getInstance()->addFile($this->getFilename());
		include $this->getFilename();
		
		$contents = ob_get_contents();
		ob_end_clean();
		Ajde_Event::trigger($this, 'afterGetContents');
		return $contents;
	}

	public function requireJsLibrary($name, $version)
	{
		$url = Ajde_Template_Resource_JsLibrary::getUrl($name, $version);
		$resource = new Ajde_Template_Resource_Remote(Ajde_Template_Resource::TYPE_JAVASCRIPT, $url);
		Ajde::app()->getDocument()->addResource($resource, Ajde_Document_Format_Html::RESOURCE_POSITION_FIRST);


	}
}