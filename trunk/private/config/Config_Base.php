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
	public $compressResources	= true;
	public $compressHtml		= true;
	public $debug 				= false;
	public $useCache			= true;
	
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
		$this->local_root = $_SERVER["DOCUMENT_ROOT"].str_replace("/index.php", "", $_SERVER["PHP_SELF"]);
		$this->site_domain = $_SERVER["SERVER_NAME"];
		$this->site_path = str_replace('index.php', '', $_SERVER["PHP_SELF"]);
		$this->site_root = $this->site_domain . $this->site_path;
	}
	
}