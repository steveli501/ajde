<?php

class Resource extends Ajde_Controller
{
	function cssDefault()
	{
		Ajde::app()->getDocument()->setLayout(new Ajde_Layout('empty'));
		$encoded = Ajde::app()->getRequest()->getParam('r');
		$resourceArray = unserialize(base64_decode($encoded));
		$resource = new Ajde_Template_Resource($resourceArray['type'], $resourceArray['base'], $resourceArray['action']);
		Ajde::app()->getResponse()->addHeader('Content-type', 'text/css');
		return $resource->getContents();
	}

}