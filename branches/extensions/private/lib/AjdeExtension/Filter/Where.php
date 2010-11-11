<?php

class AjdeExtension_Filter_Where extends AjdeExtension_Filter
{	
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
		return array(
			'where' => array(
				'sql' => $sql,
				'value' => array(spl_object_hash($this) => $this->_value)
			)
		);
	}
}