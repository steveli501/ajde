<?php

class AjdeExtension_Collection_Filter extends Ajde_Object_Standard {
		
	const FILTER_IS 	= ' = ';
	const FILTER_NOT 	= ' != ';
	const FILTER_LIKE 	= ' LIKE ';
	
	protected $_field;
	protected $_comparison;
	protected $_value;
	
	public function __construct($field, $comparison, $value)
	{
		$this->_field = $field;
		$this->_comparison = $comparison;
		$this->_value = $value;
	}
	
	public function prepare()
	{
		$sql = $this->_field . $this->_comparison . ':' . spl_object_hash($this);
		return array('sql' => $sql, 'value' => array(spl_object_hash($this) => $this->_value));
	}
}