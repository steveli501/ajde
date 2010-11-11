<?php

class AjdeExtension_Collection extends Ajde_Object_Standard implements Iterator {
	
	/**
	 * @var string
	 */
	protected $_modelName;
	
	/**
	 * @var PDO
	 */
	protected $_connection;
	
	/**
	 * @var PDOStatement
	 */
	protected $_statement;
	
	/**
	 * @var AjdeExtension_Query
	 */
	protected $_query;
	
	protected $_link = array();
	
	/**
	 * @var AjdeExtension_Db_Table
	 */
	protected $_table;
	
	protected $_filters = array();	
	protected $_filterValues = array();
	
	// For Iterator
	protected $_items = null;
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
		$this->_query = new AjdeExtension_Query();
	}
	
	public function __sleep()
	{
		return array('_modelName', '_items');
	}

	public function __wakeup()
	{
	}
	
	function rewind() {
		if (!isset($this->_items)) {
    		$this->load();
    	}
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
	 * @return AjdeExtension_Db_PDO
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
	
	/**
	 * @return PDOStatement
	 */
	public function getStatement()
	{
		return $this->_statement;
	}
	
	/**
	 * @return AjdeExtension_Query
	 */
	public function getQuery()
	{
		return $this->_query;
	}
		
	public function populate($array)
	{
		$this->reset();
		$this->_data = $array;
	}
	
	public function getLink($modelName, $value)
	{
		if (!array_key_exists($modelName, $this->_link)) {
			// TODO:
			throw new AjdeExtension_Exception('Link not defined...');
		}
		return new AjdeExtension_Filter_Link($this, $modelName, $this->_link[$modelName], $value);
	}
	
	// Chainable collection methods
	public function addFilter(AjdeExtension_Filter $filter)
	{
		$this->_filters[] = $filter;
		return $this;		
	}
	
	public function orderBy($field, $direction = self::ORDER_ASC)
	{
		$this->getQuery()->orderBy($field, $direction);
		return $this;
	}
	
	public function limit($count, $start = 0)
	{
		$this->getQuery()->limit((int) $count, (int) $start);
		return $this;
	}
	
	public function getSql()
	{		
		$this->getQuery()->select = '*';
		$this->getQuery()->from = $this->_table;
		if (!empty($this->_filters)) {
			$this->getQuery()->where = $this->getFilter('where');
		}
		if (!empty($this->_filters)) {
			$this->getQuery()->join = $this->getFilter('join');
		}		
		// ORDER BY field check
		if (isset($this->getQuery()->orderBy['field'])) {
			if (!in_array($this->getQuery()->orderBy['field'], $this->getTable()->getFieldNames())) {
				// TODO: 
				throw new AjdeExtension_Exception('Collection ordering field "'.$field.'" not valid');
			}
		}
		return $this->getQuery()->getSql();
	}
	
	public function getFilter($queryPart)
	{
		$sql = array();
		foreach($this->_filters as $filter) {
			$prepared = $filter->prepare();
			if (isset($prepared[$queryPart])) {
				$this->_filterValues = array_merge($this->_filterValues, $prepared[$queryPart]['value']);
				$sql[] = $prepared[$queryPart]['sql'];
			}			
		}
		return empty($sql) ? null : implode(' AND ', $sql);
	}	
	
	public function getFilterValues()
	{
		return $this->_filterValues;
	}
	
	// Load the collection
	public function load() {
		if (!$this->getConnection() instanceof AjdeExtension_Db_PDO) {
			// return false;
		}
		$this->_statement = $this->getConnection()->prepare($this->getSql());
		$this->_statement->execute($this->getFilterValues());
		return $this->_items = $this->_statement->fetchAll(PDO::FETCH_CLASS, $this->_modelName);
	}
}