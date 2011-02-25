<?php

class AjdeExtension_Db_Adapter_MySql extends AjdeExtension_Db_Adapter_Abstract
{
	protected $_connection = null;
	protected $_dbname = null;
	
	private $_cache = array();
	
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
	
	private function getCache($sql)
	{
		return array_key_exists($sql, $this->_cache) ? $this->_cache[$sql] : false;
	}
	
	private function saveCache($sql, $result)
	{
		$this->_cache[$sql] = $result;
		return $result;
	}
	
	public function getTableStructure($tableName)
	{
		$sql = 'SHOW FULL COLUMNS FROM '.$tableName;
		if ($cache = $this->getCache($sql)) { return $cache; }
		$statement = $this->getConnection()->query($sql);
		return $this->saveCache($sql, $statement->fetchAll()); 
	}
	
	public function getForeignKey($childTable, $parentTable) {
		$sql = sprintf("SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE
			REFERENCED_TABLE_NAME = %s AND TABLE_NAME = %s AND TABLE_SCHEMA = %s",
			$this->getConnection()->quote($parentTable),
			$this->getConnection()->quote($childTable),
			$this->getConnection()->quote($this->_dbname)
		);
		if ($cache = $this->getCache($sql)) { return $cache; }
		$statement = $this->getConnection()->query($sql);
		return $this->saveCache($sql, $statement->fetch()); 
	}
	
	public function getParents($childTable) {
		$sql = sprintf("SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE
			TABLE_NAME = %s AND TABLE_SCHEMA = %s",
			$this->getConnection()->quote($childTable),
			$this->getConnection()->quote($this->_dbname)
		);
		if ($cache = $this->getCache($sql)) { return $cache; }
		$statement = $this->getConnection()->query($sql);
		return $this->saveCache($sql, $statement->fetchAll()); 
	}
	
}