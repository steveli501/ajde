<?php

class Config_Dev extends Config_Base {
	
	public $debug				= true;
	public $useCache			= false;

	function __construct() {
		parent::__construct();
	}
	
}