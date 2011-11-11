<?php

class AjdeX_Crud_Field_File extends AjdeX_Crud_Field
{
	protected function _getHtmlAttributes()
	{
		$attributes = '';
		$attributes .= ' type="hidden" ';
		$attributes .= ' value="' . Ajde_Component_String::escape($this->getValue()) . '" ';			
		return $attributes;		
	}
	public function getSaveDir()
	{
		if (!$this->hasSaveDir()) {
			// TODO:
			throw new AjdeX_Exception('saveDir not set for AjdeX_Crud_Field_File');
		}
		return parent::getSaveDir();
	}
	
	public function getExtensions()
	{
		if (!$this->hasSaveDir()) {
			// TODO:
			throw new AjdeX_Exception('extensions not set for AjdeX_Crud_Field_File');
		}
		return parent::getExtensions();
	}
}