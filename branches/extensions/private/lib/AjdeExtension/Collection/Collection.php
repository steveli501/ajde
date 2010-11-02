<?php

class AjdeExtension_Collection extends Ajde_Object_Standard implements Iterator {
	
	protected $_connection;
	protected $_modelName;
	protected $_table;
	protected $_filters = array();
	protected $_items = array();
	protected $_position = 0;
	
	public static function register(Ajde_Controller $controller)
	{
		// Extend Ajde_Controller
		if (!Ajde_Event::has('Ajde_Controller', 'call', 'AjdeExtension_Collection::extendController')) {
			Ajde_Event::register('Ajde_Controller', 'call', 'AjdeExtension_Collection::extendController');
		}
		// Extend autoloader
		Ajde_Core_Autoloader::addDir(MODULE_DIR.$controller->getModule().'/model/');
	}
	
	public static function extendController(Ajde_Controller $controller, $method, $arguments)
	{
		// Register getCollection($name) function on Ajde_Controller
		if ($method === 'getCollection') {
			return self::getCollection($arguments[0]);
		}
		// TODO: if last triggered in event cueue, throw exception
		// throw new Ajde_Exception("Call to undefined method ".get_class($controller)."::$method()", 90006);
		// Now, we give other callbacks in event cueue chance to return
		return null; 
	}
	
	public static function getCollection($name)
	{
		$collectionName = ucfirst($name) . 'Collection';
		return new $collectionName();
	}
	
	public function __construct()
	{
		$this->_modelName = str_replace('Collection', '', get_class($this)) . 'Model';		
		$this->_connection = AjdeExtension_Db::getInstance()->getConnection();
		$tableName = strtolower(str_replace('Collection', '', get_class($this)));	
		$this->_table = AjdeExtension_Db::getInstance()->getTable($tableName);
	}
	
	function rewind() {
        $this->_position = 0;
    }

    function current() {
        return $this->_items[$this->_position];
    }

    function key() {
        return $this->_position;
    }

    function next() {
        $this->_position++;
    }

    function valid() {
        return isset($this->_items[$this->_position]);
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
	
	public function addFilter(AjdeExtension_Collection_Filter $filter)
	{
		$this->_filters[] = $filter;		
	}
	
	public function load() {
		$sql = 'SELECT * FROM '.$this->_table.' WHERE ';
		$values = array();
		$sqlFilters = array();
		foreach($this->_filters as $filter) {
			$prepareFilter = $filter->prepare();
			$sqlFilters[] = $prepareFilter['sql'];
			$values = array_merge($values, $prepareFilter['value']);
		}
		$sql .= implode(' AND ', $sqlFilters);
		$statement = $this->getConnection()->prepare($sql);
		$statement->execute($values);
		return $this->_items = $statement->fetchAll(PDO::FETCH_CLASS, $this->_modelName);
	}
}