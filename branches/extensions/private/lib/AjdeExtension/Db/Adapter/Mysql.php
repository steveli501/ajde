<?php

class AjdeExtension_Db_Adapter_MySql extends AjdeExtension_Db_Adapter_Abstract
{
	protected $_connection = null;
	protected $_dbname = null;
	
	public function __construct($dsn, $user, $password)
	{
		$dsnString = 'mysql:';
		foreach($dsn as $k => $v) {
			if ($k === 'dbname') {
				$this->_dbname = $v;
			}
			$dsnString .= $k . '=' . $v . ';'; 
		}
		parent::__construct(
			$dsnString,
			$user,
			$password, 
		    array(
		    	PDO::MYSQL_ATTR_INIT_COMMAND 	=> "SET NAMES utf8",	// Modern, please
		    	PDO::ATTR_EMULATE_PREPARES 		=> true					// Better caching		    	
		    )
		);
	}
	
	/**
	 * @return PDO
	 */
	public function getConnection()
	{
		return $this->_connection;
	}
	
	public function getTableStructure($tableName)
	{
		//$statement = $this->getConnection()->query('DESCRIBE '.$tableName);
		$statement = $this->getConnection()->query('SHOW FULL COLUMNS FROM '.$tableName);
		return $statement->fetchAll();
	}
	
	public function getForeignKey($childTable, $parentTable) {
		$sql = sprintf("SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE
			REFERENCED_TABLE_NAME = %s AND TABLE_NAME = %s AND TABLE_SCHEMA = %s",
			$this->getConnection()->quote($parentTable),
			$this->getConnection()->quote($childTable),
			$this->getConnection()->quote($this->_dbname)
		);
		$statement = $this->getConnection()->query($sql);
		return $statement->fetch();
	}
	
	public function getParents($childTable) {
		$sql = sprintf("SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE
			TABLE_NAME = %s AND TABLE_SCHEMA = %s",
			$this->getConnection()->quote($childTable),
			$this->getConnection()->quote($this->_dbname)
		);
		$statement = $this->getConnection()->query($sql);
		return $statement->fetchAll();
	}
	
}