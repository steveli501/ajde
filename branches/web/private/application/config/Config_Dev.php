<?php

class Config_Dev extends Config_Base {
	
	// Performance
	public $compressResources	= false;
	public $compressHtml		= false;
	public $debug 				= true;
	public $useCache			= false;

	function __construct() {
		parent::__construct();
	}
	
}