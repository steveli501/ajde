<?php

class Ajde_Document_Format_Html extends Ajde_Document
{
	const RESOURCE_POSITION_DEFAULT = 0;
	const RESOURCE_POSITION_FIRST = 1;
	const RESOURCE_POSITION_LAST = 2;
	
	protected $_resources = array();
	protected $_compressors = array();
	protected $_meta = array();

	public function  __construct()
	{
		/*
		 * We add the resources before the template is included, otherwise the
		 * layout resources never make it into the <head> section.
		 */
		Ajde_Event::register('Ajde_Template', 'beforeGetContents', array($this, 'autoAddResources'));
		parent::__construct();
	}

	public function render()
	{
		Ajde::app()->getResponse()->addHeader('Content-type', 'text/html');
		if (Config::get('compressHtml') == true)
		{
			Ajde_Event::register('Ajde_Layout', 'afterGetContents', 'Ajde_Document_Format_Html_Compressor::compress');
		}
		return parent::render();
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
		$code .= $this->renderResources();
		return $code;
	}

	public function renderResources()
	{
		return Config::get('compressResources') ?
			$this->renderCompressedResources() :
			$this->renderAllResources();
	}

	public function renderAllResources()
	{
		$code = '';
		foreach ($this->_resources as $resource)
		{
			/* @var $resource Ajde_Template_Resource */
			$code .= $resource->getLinkCode() . PHP_EOL;
		}
		return $code;
	}

	public function renderCompressedResources()
	{
		$code = '';
		foreach ($this->_resources as $resource)
		{
			/* @var $resource Ajde_Template_Resource */
			if ($resource instanceof Ajde_Template_Resource_Local)
			{
				if (!isset($this->_compressors[$resource->getType()]))
				{
					$this->_compressors[$resource->getType()] =
							Ajde_Template_Resource_Local_Compressor::fromType($resource->getType());
				}
				$compressor = $this->_compressors[$resource->getType()];
				/* @var $compressor Ajde_Template_Resource_Local_Compressor */
				$compressor->addResource($resource);
			}
			else
			{
				$code .= $resource->getLinkCode() . PHP_EOL;
			}
		}
		foreach ($this->_compressors as $compressor)
		{
			$resource = $compressor->process();
			$code .= $resource->getLinkCode() . PHP_EOL;
		}
		return $code;
	}

	public function getResourceTypes()
	{
		return array(
			Ajde_Template_Resource::TYPE_JAVASCRIPT,
			Ajde_Template_Resource::TYPE_STYLESHEET
		);
	}

	public function addMeta($contents)
	{
		
	}

	public function addResource(Ajde_Template_Resource $resource, $position = self::RESOURCE_POSITION_DEFAULT)
	{
		switch ($position)
		{
			case self::RESOURCE_POSITION_DEFAULT:
			case self::RESOURCE_POSITION_LAST:
				$this->_resources[] = $resource;
				break;
			case self::RESOURCE_POSITION_FIRST:
				array_unshift($this->_resources, $resource);
				break;
		}
		
	}

	public function autoAddResources(Ajde_Template $template)
	{
		foreach($this->getResourceTypes() as $resourceType) {
			if ($defaultResource = Ajde_Template_Resource_Local::lazyCreate($resourceType, $template->getBase(), 'default', $template->getFormat()))
			{
				$this->addResource($defaultResource);
			}
			if ($template->getAction() != 'default' && $actionResource = Ajde_Template_Resource_Local::lazyCreate($resourceType, $template->getBase(), $template->getAction(), $template->getFormat()))
			{
				$this->addResource($actionResource);
			}
		}
	}
	
}