<?php
class model {
	
	const TYPE_TEXT 	= 1;
	const TYPE_INT 		= 2;
	const TYPE_FLOAT 	= 3;
	const TYPE_DATETIME = 4;
	const TYPE_DATE 	= 5;
	const TYPE_TIME 	= 6;
	
	protected $_name;
	private $_structure;
	
	protected $_data = array();
	private $_id;
	
	function __construct($id = null, $autocreate = true) {
		$this->_name = get_class($this);
		if (isset($id)) {
			if ($this->loadById($id) === false && $autocreate === true) {
				$this->insert(array("id" => $id));
			}			
		}
		return $this;
	}
	
	public function __set($key, $value) {
		$this->set($key, $value);
	}

	public function __get($key) {
    	return $this->get($key);
	}
	
	public function __call($method, $arguments) {
		
		if (method_exists($this, $method)) {
			return call_user_func_array(array($this, $method), $arguments);
		}
		
		$prefix = strtolower(substr($method, 0, 3));
		$key = strtolower(substr($method, 3));
		
		if (empty($prefix) || empty($key)) {
			error::warning("Method $method on class ".get_class($this)." does not exist (no getter/setter)");
			return false;
		}
		
		if ($prefix == "get" && isset($this->_data[$key])) {
			return $this->get($key);
		} else if ($prefix == "get" && !isset($this->_data[$key])) {
			error::warning("$key not set in class ".get_class($this)." when calling $method");
			return false;
		}
		
		if ($prefix == "set") {
			return $this->set($key, $arguments[0]);
		}
		
		if ($prefix == "has") {
			return $this->has($key);
		}
		
		error::warning("Method $method on class ".get_class($this)." does not exist (wrong prefix)");
		return false;
    }
    

	/**
	 * 
	 * @param mixed $name
	 * @param mixed $value
	 * @return model
	 */
	function set($name, $value) {
		$this->_data[$name] = $value;
		return true;
	}
	
	function save() {
		$values = $this->getAll();
		if (isset($values["id"])) {
			unset($values["id"]);
		}
		return $res = $this->update($values);
	}
		
	function get($name) {
		return $this->_data[$name];
	}
	
	function has($name) {
		return !empty($this->_data[$name]);
	}
	
	function hasData() {
		return !empty($this->_data);
	}
	
	/* those might locks magic getter ?? */
	function getAll() {
		return $this->_data; 
	}

	private function setAll($array) {
		foreach ($array as $key => $value) {
			$this->set($key, $value);
		}
	}

	function setId($id) {
		$this->_id = $id;
	}
	
	function getId() {
		return $this->_id;
	}
	
	static public function getDbLink() {
		return db::getInstance()->getLink();
	}
	
	/**
	 * @param integer $id
	 * @return model
	 */
	function loadByField($field, $value) {
		$db = db::getInstance();
		$db->select("SELECT * FROM $this->_name WHERE $field = '".addslashes($value)."'");
		$res = $db->get_row();
		if ($res) {
			$this->setAll($res);
			$this->setId($res["id"]);
			return $this;
		} else {
			return false;
		}
	}
	
	function loadBySQLWhere($sqlWhere) {
		$db = db::getInstance();
		$db->select("SELECT * FROM $this->_name WHERE $sqlWhere");
		$res = $db->get_row();
		if ($res) {
			$this->setAll($res);
			$this->setId($res["id"]);
			return $this;
		} else {
			return false;
		}
	}
	
	/**
	 * @param integer $id
	 * @return model
	 */
	private function loadById($id) {
		$db = db::getInstance();
		$db->select("SELECT * FROM $this->_name WHERE id = '$id'");
		$res = $db->get_obj();
		if ($res) {
			$this->setAll($res[0]);
			$this->setId($id);
			return $this;
		} else {
			return false;
		}
	}
	
	// blocks magic getter
	/*function getName() {
		return $this->_name;
	}*/
	
	function sql($sql) {
		$db = db::getInstance();
		$db->select($sql);
		return $db->get_obj();
	}
	
	function sqlOne($sql) {
		$db = db::getInstance();
		return $db->select_one($sql);
	}
	
	function sqlRow($sql) {
		$db = db::getInstance();
		$db->select_one($sql);
		$obj = $db->get_obj();
		return $obj[0];
	}
	
	function query(query $query) {
		$db = db::getInstance();
		$db->select($query->render());
		return $db->get_obj();
	}
	
	// blocks magic getter
	/*function getStructure() {
		$db = db::getInstance();
		$db->select("DESCRIBE $table");
		$this->_structure = $db->get_obj();
		return $this->_structure;	
	}*/
	
	function insert($array = array(), $replace = false) {
		$db = db::getInstance();
		$id = $db->insert_array($this->_name, $array, $replace);
		$this->setAll($array);
		if ($id === true) {
			$this->setId($array["id"]);
		} else {
			$this->setId($id);
		}
		return $id;
	}

	function replace($array = array()) {		
		return $this->insert($array, true);
	}
	
	function update($array) {
		$db = db::getInstance();
		$this->setAll($array);
		return $db->update_array($this->_name, $array, "id = '".$this->getId()."'");
	}
	
	function parse() {
		return false;
	}
	
	function delete() {
		$db = db::getInstance();
		$res = $db->update_sql("DELETE FROM $this->_name WHERE id = '".$this->getId()."'");
		if ($res) {
			$this->setId(null);
			$this->_data = array();
			return true;
		} else {
			return false;
		}
	}
	
	function hit() {
		if ($this->hasId() && $this->hasHit()) {
			$db = db::getInstance();
			$db->update_sql("UPDATE $this->_name SET hits = (hits +  1) WHERE id = ".(int) $this->getId());
		} else {
			return false;
		}
	}
	
	
	// blocks magic getter
	/* function getType($name) {
		foreach($this->_structure as $column) {
			if (strtolower($column->Field) == strtolower($name)) {
				// do something
			}
		}
	}*/
	
}

?>