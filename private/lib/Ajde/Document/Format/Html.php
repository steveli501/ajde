<?php

class Ajde_Document_Format_Html extends Ajde_Document
{
	protected $_resources = array();
	protected $_meta = array();

	public function  __construct()
	{
		Ajde_Event::register('Ajde_Template', 'beforeGetContents', array($this, 'autoAddResources'));
		parent::__construct();
	}

	/**
	 *
	 * @return string
	 */
	public function getHead()
	{
		return $this->renderHead();
	}

	public function renderHead()
	{
		$code = '';
		foreach ($this->_resources as $resource) {
			/* @var $resource Ajde_Template_Resource */
			$code .= $resource->getLinkCode() . PHP_EOL;
		}
		return $code;
	}

	public function getResourceTypes()
	{
		return array('css', 'js');
	}

	public function addMeta($contents)
	{
		
	}

	public function addResource(Ajde_Template_Resource $resource)
	{
		$this->_resources[] = $resource;
	}

	public function autoAddResources(Ajde_Template $template)
	{
		foreach($this->getResourceTypes() as $resourceType) {
			if ($defaultResource = Ajde_Template_Resource::lazyCreate($resourceType, $template->getBase(), 'default', $template->getFormat()))
			{
				$this->addResource($defaultResource);
			}
			if ($template->getAction() != 'default' && $actionResource = Ajde_Template_Resource::lazyCreate($resourceType, $template->getBase(), $template->getAction(), $template->getFormat()))
			{
				$this->addResource($actionResource);
			}
		}
	}
	
}