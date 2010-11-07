<?php

class AjdeExtension_Db_Table extends Ajde_Object_Standard
{
	protected $_connection;
	protected $_name;	
	protected $_fields;
		
	public function __construct($name)
	{
		$this->_name = $name;
		$this->_connection = AjdeExtension_Db::getInstance()->getConnection();
		$this->initTableStructure();
	}
	
	/**
	 * @return PDO
	 */
	public function getConnection()
	{
		return $this->_connection;
	}
	
	public function initTableStructure() 
	{
		$structure = AjdeExtension_Db::getInstance()->getAdapter()->getTableStructure($this->_name); 
		foreach($structure as $field) {
			$fieldName = $field['Field'];
			$fieldType = $field['Type'];
			$fieldIsRequired = $field['Null'] === 'NO';
			$fieldIsPK = $field['Key'] === 'PRI';
			$fieldDefault = $field['Default'];
			$fieldIsAutoIncrement = $field['Extra'] === 'auto_increment';
			
			$this->_fields[$fieldName] = array(
				'name' => $fieldName,
				'type' => $fieldType,
				'isRequired' => $fieldIsRequired,
				'isPK' => $fieldIsPK,
				'default' => $fieldDefault,
				'isAutoIncrement' => $fieldIsAutoIncrement
			);
		}
	}
	
	public function getPK()
	{
		foreach($this->_fields as $field) {
			if ($field['isPK'] === true) {
				return $field['name'];
			}
		}
		return false;
	}
	
	public function getFK(AjdeExtension_Db_Table $parent) {
		$fk = AjdeExtension_Db::getInstance()->getAdapter()->getForeignKey((string) $this, (string) $parent);
		return array('field' => $fk['COLUMN_NAME'], 'parent_field' => $fk['REFERENCED_COLUMN_NAME']);		
	}
	
	public function getParents() {
		$parents = AjdeExtension_Db::getInstance()->getAdapter()->getParents((string) $this);
		$parentTables = array();
		foreach($parents as $parent) {
			if (isset($parent['REFERENCED_TABLE_NAME'])) {
				$parentTables[] = $parent['REFERENCED_TABLE_NAME'];
			}
		}
		return $parentTables;		
	}
	
	public function getFieldNames()
	{
		return array_keys($this->_fields);		 
	}
	
	public function __toString()
	{
		return $this->_name;
	}
}