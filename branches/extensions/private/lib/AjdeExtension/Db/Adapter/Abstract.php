<?php

abstract class AjdeExtension_Db_Adapter_Abstract
{	
	public function __construct($dsn, $user, $password, $options)
	{
		try {
			$connection = new PDO($dsn, $user, $password, $options);
		} catch (Exception $e) {
			// Disable trace on this exception to prevent exposure of sensitive
			// data
			// TODO: exception
			throw new Ajde_Exception('Could not connect to database', 0, false);
		}
		$this->_connection = $connection;
	} 
	
	abstract public function getConnection();
	abstract public function getTableStructure($tableName);
	abstract public function getForeignKey($childTable, $parentTable);
	abstract public function getParents($childTable);
}