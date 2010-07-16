<?php

class Resource extends Ajde_Controller
{
	function localCss()
	{
		return $this->getResource();
	}

	function localJs()
	{
		return $this->getResource();
	}

	function getResource()
	{
		// get resource from request
		$encoded = Ajde::app()->getRequest()->getParam('r');
		$resource = Ajde_Template_Resource_Local::fromLinkUrl($encoded);

		// prepare document
		Ajde::app()->getDocument()->setLayout(new Ajde_Layout('empty'));
		Ajde::app()->getResponse()->addHeader('Content-type', $resource->getContentType());
		return $resource->getContents();
	}

}