<?php

abstract class AjdeX_Model_ValidatorAbstract extends Ajde_Object_Standard
{	
	protected $_value = null;
		
	/**
	 * Getters and setters
	 */
	
	public function getName()			{ return parent::getName(); }
	public function getDbType()			{ return parent::getDbType(); }
	public function getLabel()			{ return parent::getLabel(); }
	public function getLength()			{ return parent::getLength(); }
	public function getIsRequired()		{ return parent::getIsRequired(); }
	public function getDefault()		{ return parent::getDefault(); }
	public function getIsAutoIncrement(){ return parent::getIsAutoIncrement(); }
	
	public function getValue()
	{
		return $this->_value;
	}
	
	public function validate($fieldOptions, $value)
	{
		$this->_value = $value;
		
		/* options */
		foreach($fieldOptions as $key => $value) {
			$this->set($key, $value);
		}
		return $this->_validate();
	}
	
	/**
	 * @return array('valid' => true|false, ['error' => (string)]);
	 */
	abstract protected function _validate();
}