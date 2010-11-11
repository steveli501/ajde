<?php

class AjdeExtension_Query extends Ajde_Object_Standard
{
	const ORDER_ASC 	= 'ASC';
	const ORDER_DESC 	= 'DESC';
	
	public $select = '*';
	public $from = null;
	public $where = null;
	public $join = null;	
	public $orderBy = array('field' => null, 'direction' => self::ORDER_ASC);
	public $limit = array('start' => null, 'count' => null);
	
	public function select($select)
	{
		$this->_select = $select;		
	}
	
	public function from($from)
	{
		$this->_from = $from;
	}
	
	public function where($where)
	{
		$this->_where = $where;
	}
	
	public function join($join)
	{
		$this->_join = $join;
	}
		
	public function orderBy($field, $direction = self::ORDER_ASC)
	{
		$direction = strtoupper($direction);
		if (!in_array($direction, array(self::ORDER_ASC, self::ORDER_DESC))) {
			// TODO: 
			throw new AjdeExtension_Exception('Collection ordering direction "'.$direction.'" not valid');
		}
		$this->_order = array('field' => $field, 'direction' => $direction);
	}
	
	public function limit($count, $start = 0)
	{
		$this->_limit = array('count' => (int) $count, 'start' => (int) $start);
	}
	
	public function getSql()
	{
		$sql  		 =  'SELECT ' . $this->select;
		$sql 		.= ' FROM ' . $this->from;
		if (isset($this->where)) {
			$sql	.= ' WHERE ' . $this->where;
		}
		if (isset($this->join)) {
			$sql	.= ' INNER JOIN ' . $this->join;
		}		
		if (isset($this->orderBy['field'])) {
			$sql 	.= ' ORDER BY '.$this->orderBy['field'].' '.$this->orderBy['direction'];
		}
		if (isset($this->limit['count']) && !isset($this->limit['start'])) {
			$sql 	.= ' LIMIT '.$this->limit['count'];
		} elseif (isset($this->limit['count']) && isset($this->limit['start'])) {
			$sql 	.= ' LIMIT '.$this->limit['start'].', '.$this->limit['count'];	
		}		
		return $sql;
	}
}
