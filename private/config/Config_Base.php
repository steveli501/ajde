<?php

class Config_Base
{	
	// Site parameters
	public $ident				= "ajde";
	public $sitename 			= "ajde open web framework";	
	public $version 			= array(
									"number" => "0",
									"name" => "alpha"
									);
	public $defaultRoute		= array(
									"module" => "home",
									"action" => "view",
									"format" => "html"
									);									
	public $lang 				= "en";
	public $layout 				= "default";
	
	// Performance
	public $compressResources	= false;
	public $debug 				= false;
	
	// Database settings
	public $db_host 			= "localhost";
	public $db_user 			= "ajde";
	public $db_password 		= "ajde";
	public $db_db 				= "ajde";

	// Which modules should we call on bootstrapping?
	public $bootstrap			= array(
									"Ajde_Exception_Handler",
									"Ajde_Session",
									);

	function __construct()
	{
		$this->site_root = $_SERVER["DOCUMENT_ROOT"].str_replace("/index.php", "", $_SERVER["PHP_SELF"]);
		$this->site_url = $_SERVER["SERVER_NAME"];
	}
	
}