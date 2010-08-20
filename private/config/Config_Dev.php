<?php

class Config_Dev extends Config_Base {
	
	// Performance
	public $compressResources	= false;
	public $compressHtml		= false;
	public $debug 				= true;
	public $useCache			= true;

	function __construct() {
		parent::__construct();
	}
	
}