<?php

class AjdeExtension_Db_Adapter_MySql extends AjdeExtension_Db_Adapter_Abstract
{
	protected $_connection = null;
	
	public function __construct($dsn, $user, $password)
	{
		$dsnString = 'mysql:';
		foreach($dsn as $k => $v) {
			$dsnString .= $k . '=' . $v . ';'; 
		}
		parent::__construct(
			$dsnString,
			$user,
			$password, 
		    array(
		    	PDO::MYSQL_ATTR_INIT_COMMAND 	=> "SET NAMES utf8", 	// Modern, please
		    	PDO::ATTR_PERSISTENT 			=> true,				// Fast, please
		    	PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION // Exceptions, please
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
		$statement = $this->getConnection()->query('DESCRIBE '.$tableName);
		return $statement->fetchAll();
	}
}