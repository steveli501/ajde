<?php

class AjdeExtension_Filter_WhereGroup extends AjdeExtension_Filter
{		
	protected $_filters;
	protected $_operator;
	
	public function __construct($operator = AjdeExtension_Query::OP_AND)
	{
		$this->_operator = $operator;
	}
	
	public function addFilter(AjdeExtension_Filter_Where $filter)
	{
		$this->_filters[] = $filter;
	}
	
	public function prepare(AjdeExtension_Db_Table $table = null)
	{
		$sqlWhere = '';
		$first = true;
		$values = array();
		foreach($this->_filters as $filter) {
			$prepared = $filter->prepare($table);
			foreach($prepared as $queryPart => $v) {
				switch ($queryPart) {
					case 'where':
						if ($first === false) {
							$sqlWhere .= ' ' . $v['arguments'][1];
						}
						$sqlWhere .= ' ' . $v['arguments'][0];
						$first = false;
						if (isset($v['values'])) {
							$values = array_merge($values, $v['values']);
						}
						break;
				}
			}				
		}
		
		return array(
			'where' => array(
				'arguments' => array('(' . $sqlWhere . ')', $this->_operator),
				'values' => $values
			)
		);
	}
}