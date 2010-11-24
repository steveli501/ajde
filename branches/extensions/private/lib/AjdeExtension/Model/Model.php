<?php

class AjdeExtension_Model extends Ajde_Object_Standard
{	
	protected $_connection;
	protected $_table;
	
	protected $_autoloadParents = false;
	protected $_displayField = null;
	
	public static function register($controller)
	{
		// Extend Ajde_Controller
		if (!Ajde_Event::has('Ajde_Controller', 'call', 'AjdeExtension_Model::extendController')) {
			Ajde_Event::register('Ajde_Controller', 'call', 'AjdeExtension_Model::extendController');
		}
		// Extend autoloader
		if ($controller instanceof Ajde_Controller) {
			Ajde_Core_Autoloader::addDir(MODULE_DIR.$controller->getModule().'/model/');
		} elseif ($controller === '*') {
			self::registerAll();
		} else {
			Ajde_Core_Autoloader::addDir(MODULE_DIR.$controller.'/model/');
		}		
	}
	
	public static function registerAll()
	{
		$dirs = Ajde_FS_Find::findFiles(MODULE_DIR, '*/model');
		foreach($dirs as $dir) {
			Ajde_Core_Autoloader::addDir($dir . '/');
		}		
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
		return $this->getPK();		
	}
	
	public function __sleep()
	{
		return array('_autoloadParents', '_data');
	}

	public function __wakeup()
	{
	}
	
	public function getPK()
	{
		return $this->get($this->getTable()->getPK()); 
	}
	
	public function getDisplayField()
	{
		if (isset($this->_displayField)) {
			return $this->_displayField;
		} else {
			return current($this->getTable()->getFieldNames());
		}
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
	
	public function getValues() {
		$return = array();
		foreach($this->_data as $k => $v) {
			if ($v instanceof AjdeExtension_Model) {
				$return[$k] = $v->getValues();
			} else {
				$return[$k] = $v;
			}
		}
		return $return;
	}
	
	public function loadByPK($value)
	{
		$pk = $this->getTable()->getPK();
		$sql = 'SELECT * FROM '.$this->_table.' WHERE '.$pk.' = ? LIMIT 1';
		$statement = $this->getConnection()->prepare($sql);
		$statement->execute(array($value));
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		if (!$result) {
			return false;
		}
		$this->populate($result);
		if ($this->_autoloadParents === true) {
			$this->loadParents();
		}
		return $result;
	}
	
	public function save()
	{
		return false;
	}
	
	public function delete()
	{
		$id = $this->getPK();
		$pk = $this->getTable()->getPK();
		$sql = 'DELETE FROM '.$this->_table.' WHERE '.$pk.' = ? LIMIT 1';
		$statement = $this->getConnection()->prepare($sql);
		return $statement->execute(array($id));
	}
	
	public function getParents()
	{
		return $this->getTable()->getParents();
	}
	
	public function loadParents()
	{
		foreach($this->getParents() as $parentTableName) {
			$this->loadParent($parentTableName);
		}
	}
	
	public function loadParent($parent)
	{
		if (empty($this->_data)) {
			// TODO:
			throw new AjdeExtension_Exception('Model not loaded when loading parent');
		}
		if ($parent instanceof AjdeExtension_Model) {
			$parent = $parent->getTable();
		} elseif (!$parent instanceof AjdeExtension_Db_Table) {
			$parent = new AjdeExtension_Db_Table($parent);
		}
		$fk = $this->getTable()->getFK($parent);
		if (!$this->has($fk['field'])) {
			// No value for FK field
			return false;
		}
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