<?php

class Config_Dev extends Config_Base {
	
	public $debug				= true;
	public $useCache			= true;

	function __construct() {
		parent::__construct();
	}
	
}