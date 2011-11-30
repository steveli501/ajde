<?php

abstract class AjdeX_Filter extends Ajde_Object_Standard
{		
	const FILTER_IS			= ' IS ';
	const FILTER_NOT		= ' IS NOT ';	
	const FILTER_EQUALS		= ' = ';
	const FILTER_EQUALSNOT	= ' != ';
	const FILTER_GREATER	= ' > ';
	const FILTER_GREATEROREQUAL	= ' >= ';
	const FILTER_LESS		= ' < ';
	const FILTER_LESSOREQUAL= ' <= ';
	const FILTER_LIKE		= ' LIKE ';
	
	abstract public function prepare(AjdeX_Db_Table $table = null);
}