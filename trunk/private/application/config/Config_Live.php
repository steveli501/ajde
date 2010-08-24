<?php

class Config_Live extends Config_Base {
	
	public $debug				= false;
	public $useCache			= true;

	function __construct() {
		parent::__construct();
	}
	
}