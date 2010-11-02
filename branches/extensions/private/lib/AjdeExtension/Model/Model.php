<?php

class AjdeExtension_Model extends Ajde_Object_Standard {
	
	protected $_connection;
	protected $_table;
	
	public static function register(Ajde_Controller $controller)
	{
		// Extend Ajde_Controller
		if (!Ajde_Event::has('Ajde_Controller', 'call', 'AjdeExtension_Model::extendController')) {
			Ajde_Event::register('Ajde_Controller', 'call', 'AjdeExtension_Model::extendController');
		}
		// Extend autoloader
		Ajde_Core_Autoloader::addDir(MODULE_DIR.$controller->getModule().'/model/');
	}
	
	public static function extendController(Ajde_Controller $controller, $method, $arguments)
	{
		// Register getModel($name) function on Ajde_Controller
		if ($method === 'getModel') {
			return self::getModel($arguments[0]);
		}
		// TODO: if last triggered in event cueue, throw exception
		// throw new Ajde_Exception("Call to undefined method ".get_class($controller)."::$method()", 90006);
		// Now, we give other callbacks in event cueue chance to return
		return null;  
	}
	
	public static function getModel($name)
	{
		$modelName = ucfirst($name) . 'Model';
		return new $modelName();
	}
	
	public function __construct()
	{
		$tableName = strtolower(str_replace('Model', '', get_class($this)));
		$this->_connection = AjdeExtension_Db::getInstance()->getConnection();	
		$this->_table = AjdeExtension_Db::getInstance()->getTable($tableName);		
	}
	
	public function __set($name, $value) {
        $this->set($name, $value);
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function __isset($name) {
        return $this->has($name);
    }
	
	/**
	 * @return AjdeExtension_Db
	 */
	public function getConnection()
	{
		return $this->_connection;
	}
	
	/**
	 * @return AjdeExtension_Db_Table
	 */
	public function getTable()
	{
		return $this->_table;
	}
	
	public function populate($array)
	{
		$this->reset();
		$this->_data = $array;
	}
	
	public function loadByPK($value, $autoCreate = false) {
		$pk = $this->getTable()->getPK();
		$sql = 'SELECT * FROM '.$this->_table.' WHERE '.$pk.' = ? LIMIT 1';
		$statement = $this->getConnection()->prepare($sql);
		$statement->execute(array($value));
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		$this->populate($result);		
	}
}