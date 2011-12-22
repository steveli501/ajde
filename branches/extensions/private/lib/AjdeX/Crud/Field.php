<?php

abstract class AjdeX_Crud_Field extends Ajde_Object_Standard
{
	private $_crud;
	private $_type;
	
	public function __construct(AjdeX_Crud $crud, $fieldOptions) {
		$explode = explode('_', get_class($this));
		end($explode);
		$this->_type = strtolower(current($explode));
		$this->_crud = $crud;

		/* options */
		foreach($fieldOptions as $key => $value) {
			$this->set($key, $value);
		}		
	}
	
	/**
	 * Getters and setters
	 */
	
	public function getName()			{ return parent::getName(); }
	public function getDbType()			{ return parent::getDbType(); }
	public function getValue()			{ return parent::getValue(); }
	public function getLabel()			{ return parent::getLabel(); }
	public function getLength()			{ return parent::getLength(); }
	public function getIsRequired()		{ return parent::getIsRequired(); }
	public function getDefault()		{ return parent::getDefault(); }
	public function getIsAutoIncrement(){ return parent::getIsAutoIncrement(); }
	
	/**
	 * Template functions
	 */
	
	public function getHtml()
	{
		$template = $this->_getFieldTemplate();
		$template->assign('field', $this);
		return $template->render();
	}
	
	public function getInput($id = null)
	{
		$template = $this->_getInputTemplate();
		$template->assign('field', $this);
		$template->assign('id', $id);
		return $template->render();
	}
	
	private function _getFieldTemplate()
	{
		return $this->_getTemplate('field');
	}
	
	private function _getInputTemplate()
	{
		return $this->_getTemplate('field/' . $this->_type);
	}
	
	private function _getTemplate($action)
	{
		$template = null;
		if (Ajde_Template::exist(MODULE_DIR . '_core/', 'crud/' . $action) !== false) {
			$template = new Ajde_Template(MODULE_DIR . '_core/', 'crud/' . $action);
			Ajde::app()->getDocument()->autoAddResources($template);
		}
		if ($this->_hasCustomTemplate($action)) {			
			$base = $this->_getCustomTemplateBase();
			$action = $this->_getCustomTemplateAction($action);
			$template = new Ajde_Template($base, $action);
		}
		if ($template instanceof Ajde_Template) {
			return $template;
		} else {
			// TODO:
			throw new AjdeX_Exception('No crud template found for field ' . $action);
		}		
	}
		
	private function _hasCustomTemplate($action)
	{
		$base = $this->_getCustomTemplateBase();
		$action = $this->_getCustomTemplateAction($action);
		return Ajde_Template::exist($base, $action) !== false;
	}
	
	private function _getCustomTemplateBase()
	{
		return MODULE_DIR . $this->_crud->getCustomTemplateModule() . '/';
	}
	
	private function _getCustomTemplateAction($action)
	{
		return 'crud/' . (string) $this->_crud->getModel()->getTable() . '/' . $action;
	}
	
	/** 
	 * HTML functions
	 */
	
	public function getHtmlRequired()
	{
		//return '<span class="required">*</span>';
		return '';
	}
	
	public function getHtmlPK()
	{
		return " <img src='public/images/_core/crud/key_16.png' style='vertical-align: middle;' title='Primary key' />";
	}
	
	public function getHtmlAttributes()
	{
		$attributes = '';
		if (method_exists($this, '_getHtmlAttributes')) {
			$attributes .= $this->_getHtmlAttributes();
		}
		$attributes .= ' name="' . $this->getName() . '" ';
		return $attributes;
	}
	
}