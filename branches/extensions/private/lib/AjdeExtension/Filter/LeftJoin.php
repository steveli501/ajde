<?php

class AjdeExtension_Filter_LeftJoin extends AjdeExtension_Filter
{	
	protected $_table;
	protected $_ownerField;
	protected $_childField;
	
	public function __construct($table, $ownerField, $childField)
	{
		$this->_table = $table;
		$this->_ownerField = $ownerField;
		$this->_childField = $childField;
	}
	
	public function prepare(AjdeExtension_Db_Table $table = null)
	{
		$sql = $this->_table . ' ON ' . $this->_ownerField . ' = ' . $this->_childField;
		return array(
			'join' => array(
				'arguments' => array($sql, AjdeExtension_Query::JOIN_LEFT),
				'values' => array()
			)
		);
	}
}