<?php
class query {
	
	private $select;
	private $table;
	private $where;
	private $order;
	
	function __construct($array = array()) {
		foreach($array as $key => $item) {
			$this->set($this->$key, $item);
		}
	}
	
	function setSelect($mixed) {
		$this->set($this->select, $mixed);
	}
	
	function addSelect($mixed) {
		$this->add($this->select, $mixed);
	}
	
	function setTable($mixed) {
		$this->set($this->table, $mixed);
	}
	
	function addTable($mixed) {
		$this->add($this->table, $mixed);
	}
	
	function setWhere($mixed) {
		$this->set($this->where, $mixed);
	}
	
	function addWhere($mixed) {
		$this->add($this->where, $mixed);
	}
	
	function setOrder($array) {
		foreach($array as $item) {
			if (is_array($item)) {
				$this->order = $array();
				continue;
			} else {
				$this->order = array($array);
				continue;
			}
		}
	}
	
	function addOrder($array) {
		foreach($array as $item) {
			if (is_array($item)) {
				$this->order = array_merge($this->order, $array());
				continue;
			} else {
				$this->order[] = $array;
				continue;
			}
		}
	}
	
	private function set(&$var, $mixed) {
		if (is_array($mixed)) {
			$var = $mixed;
		} else {
			$var = array($mixed);
		}
	}
	
	private function add(&$var, $mixed) {
		if (is_array($mixed)) {
			$var = array_merge($var, $mixed);
		} else {
			$var[] = $mixed;
		}
	}
	
	function render() {
		$sql = 
			"SELECT ".
			implode(", ", $this->select).
			" FROM ".
			implode(", ", $this->table);
		if ($this->where) {
			$sql .=
				" WHERE ".
				implode(" AND ", $this->where);
		}
		if ($this->order) {
			$sql .=
				" ORDER BY ";
				foreach($this->order as $order) {
					if (is_array($order)) {
						$sql .= $order["by"] . " " . strtoupper($order["dir"]);
					} else {
						$sql .= $order . " ASC";
					}
				}
				
		}
		return $sql;
	}
	
}
?>