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
		include $this->getFilename();
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	public function autoAddResources()
	{
		$document = Ajde::app()->getDocument();
		foreach($document->getResourceTypes() as $resourceType) {
			if ($defaultResource = Ajde_Template_Resource::lazyCreate($resourceType, $this->getBase(), 'default', $this->getFormat()))
			{
				$document->addResource($defaultResource);
			}
			if ($this->getAction() != 'default' && $actionResource = Ajde_Template_Resource::lazyCreate($resourceType, $this->getBase(), $this->getAction(), $this->getFormat()))
			{
				$document->addResource($actionResource);
			}
		}
	}

	public function getResourceFilename($resourceType, $action)
	{
		return $this->getBase() . 'res/' . $resourceType . '/' . $action . '.' . $resourceType;
	}
	
	protected function checkResourceExist($resourceType, $action)
	{
		$filename = $this->getResourceFilename($resourceType, $action);
		if (!file_exists($filename)) {
			return $filename;
		}
		return false;

	}
}