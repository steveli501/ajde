<?php

class AjdeExtension_Collection extends Ajde_Object_Standard implements Iterator {
	
	const ORDER_ASC 	= 'ASC';
	const ORDER_DESC 	= 'DESC';
	
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
	 * @var AjdeExtension_Db_Table
	 */
	protected $_table;
	
	protected $_filters = array();	
	protected $_limit = array('start' => null, 'count' => null);
	protected $_order = array('field' => null, 'direction' => self::ORDER_ASC);
	
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
	
	/**
	 * @return PDOStatement
	 */
	public function getStatement()
	{
		return $this->_statement;
	}
		
	public function populate($array)
	{
		$this->reset();
		$this->_data = $array;
	}
	
	// Chainable collection methods
	public function addFilter(AjdeExtension_Collection_Filter $filter)
	{
		$this->_filters[] = $filter;
		return $this;		
	}
	
	public function limit($count, $start = 0)
	{
		$this->_limit = array('count' => (int) $count, 'start' => (int) $start);
		return $this;
	}
	
	public function order($field, $direction = self::ORDER_ASC)
	{
		$direction = strtoupper($direction);
		if (!in_array($direction, array(self::ORDER_ASC, self::ORDER_DESC))) {
			// TODO: 
			throw new AjdeExtension_Exception('Collection ordering direction "'.$direction.'" not valid');
		}
		if (!in_array($field, $this->getTable()->getFieldNames())) {
			// TODO: 
			throw new AjdeExtension_Exception('Collection ordering field "'.$field.'" not valid');
		}
		$this->_order = array('field' => $field, 'direction' => $direction);
		return $this;
	}
	
	public function getSql()
	{
		// SELECT all
		$sqlSelect = 'SELECT * FROM '.$this->_table;
		// WHERE from filters
		$sqlWhere = '';
		if (!empty($this->_filters)) {
			$sqlWhere = ' WHERE ' . $this->getFilterSql();
		}
		// ORDER BY
		$sqlOrderBy = '';
		if (isset($this->_order['field'])) {
			$sqlOrderBy = ' ORDER BY '.$this->_order['field'].' '.$this->_order['direction'];
		}
		// LIMIT
		$sqlLimit = '';
		if (isset($this->_order['count']) && !isset($this->_order['start'])) {
			$sqlLimit = ' LIMIT '.$this->_limit['count'];
		} elseif (isset($this->_order['count']) && isset($this->_order['start'])) {
			$sqlLimit = ' LIMIT '.$this->_limit['start'].', '.$this->_limit['count'];	
		}		
		// Build SQL
		return $sqlSelect . $sqlWhere . $sqlOrderBy . $sqlLimit;
	}
	
	public function getFilterSql()
	{
		$sql = array();
		foreach($this->_filters as $filter) {
			$prepareFilter = $filter->prepare();
			$sql[] = $prepareFilter['sql'];
		}
		return implode(' AND ', $sql);
	}
	
	public function getFilterValues()
	{
		$values = array();
		foreach($this->_filters as $filter) {
			$prepareFilter = $filter->prepare();
			$values = array_merge($values, $prepareFilter['value']);
		}
		return $values;
	}
	
	// Load the collection
	public function load() {
		$this->_statement = $this->getConnection()->prepare($this->getSql());
		$this->_statement->execute($this->getFilterValues());
		return $this->_items = $this->_statement->fetchAll(PDO::FETCH_CLASS, $this->_modelName);
	}
}