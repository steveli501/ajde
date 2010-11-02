<?php

class AjdeExtension_Db extends Ajde_Object_Singleton
{
	protected $_adapter = null;
	protected $_tables = null;
	
	public static function getInstance()
	{
    	static $instance;
    	return $instance === null ? $instance = new self : $instance;
	}
		
	public function __construct()
	{
		$adapterName = 'AjdeExtension_Db_Adapter_' . ucfirst(Config::get('dbAdapter'));
		$dsn = Config::get('dbDsn');
		$user = Config::get('dbUser');
		$password = Config::get('dbPassword');
		$this->_adapter = new $adapterName($dsn, $user, $password);
	}
	
	/**
	 * @return AjdeExtension_Db_Adapter_Abstract
	 */
	public function getAdapter()
	{
		return $this->_adapter;
	}
	
	/**
	 * @return PDO
	 */
	function getConnection()
	{
		return $this->_adapter->getConnection();
	}
	
	function getTable($tableName)
	{
		if (!isset($this->_tables[$tableName])) {
			$this->_tables[$tableName] = new AjdeExtension_Db_Table($tableName);
		}
		return $this->_tables[$tableName];
	}
}