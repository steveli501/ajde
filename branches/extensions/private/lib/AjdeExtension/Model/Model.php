<?php

class AjdeExtension_Model extends Ajde_Object_Standard {
	
	protected $_connection;
	protected $_table;
	
	protected $_autoloadParents = false;
	
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
	
	public function __toString() {
		foreach($this->_data as $value) {
			if (is_string($value)) {
				return $value;
			}
		}
		throw new AjdeExtension_Exception('Object of class '.get_class($this).' could not be converted to string');
		return false;
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
		// TODO: parse array and typecast to match fields settings
		$this->_data = $array;
	}
	
	public function loadByPK($value) {
		$pk = $this->getTable()->getPK();
		$sql = 'SELECT * FROM '.$this->_table.' WHERE '.$pk.' = ? LIMIT 1';
		$statement = $this->getConnection()->prepare($sql);
		$statement->execute(array($value));
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		$this->populate($result);
		if ($this->_autoloadParents === true) {
			foreach($this->getParents() as $parentTableName) {
				$this->loadParent($parentTableName);
			}
		}
	}
	
	public function getParents() {
		return $this->getTable()->getParents();
	}
	
	public function loadParent($parent) {
		if (empty($this->_data)) {
			// TODO:
			throw new AjdeExtension_Exception('Model not loaded when loading parent');
		}
		if ($parent instanceof AjdeExtension_Model) {
			$parent = $parent->getTable();
		} elseif (!$parent instanceof AjdeExtension_Db_Table) {
			$parent = new AjdeExtension_Db_Table($parent);
		}
		if ($this->has((string) $parent)) {
			// TODO:
			throw new AjdeExtension_Exception('Field '.(string) $parent.' already exists when loading parent with the same name');
		}
		$fk = $this->getTable()->getFK($parent);
		$parentModelName = ucfirst((string) $parent) . 'Model';
		$parentModel = new $parentModelName();
		if ($parentModel->getTable()->getPK() != $fk['parent_field']) {
			// TODO:
			throw new AjdeExtension_Exception('Constraints on non primary key fields are currently not supported');
		}
		$parentModel->loadByPK($this->get($fk['field']));
		$this->set((string) $parent, $parentModel);
	}
}