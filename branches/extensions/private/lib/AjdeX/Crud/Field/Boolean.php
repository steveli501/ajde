<?php

class AjdeX_Crud_Field_Boolean extends AjdeX_Crud_Field
{
	protected function _getHtmlAttributes()
	{
		$attributes = '';
		$attributes .= ' type="hidden" ';
		$attributes .= ' value="'.$this->getValue().'" ';
		return $attributes;		
	}
}