<?php

class Resource extends Ajde_Controller
{
	function localDefault()
	{
		return $this->_getLocalResource();
	}

	function compressedDefault()
	{
		return $this->_getCompressedResource();
	}

	protected function _getLocalResource()
	{
		return $this->_getResource('Ajde_Template_Resource_Local');
	}

	protected function _getCompressedResource()
	{
		return $this->_getResource('Ajde_Template_Resource_Local_Compressed');
	}

	protected function _getResource($className)
	{
		// get resource from request
		$encoded = Ajde::app()->getRequest()->getParam('id');
		$resource = $className::fromLinkUrl($encoded);
		return $resource->getContents();
	}

}