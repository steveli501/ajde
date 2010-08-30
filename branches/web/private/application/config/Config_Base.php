<?php

class Config_Base
{	
	// Site parameters
	public $ident				= "ajde";
	public $sitename 			= "Ajde web framework";	
	public $version 			= array(
									"number" => "0",
									"name" => "alpha"
									);
	public $homepageRoute		= "home.html";
	public $defaultRouteParts	= array(
									"module" => "home",
									"action" => "view",
									"format" => "html"
									);       
	public $lang 				= "en";
	public $layout 				= "ajde";
	
	// Performance
	public $compressResources	= true;
	public $compressHtml		= true;
	public $debug 				= false;
	public $useCache			= true;
	
	// Database settings
	public $db_host 			= "localhost";
	public $db_user 			= "user";
	public $db_password 		= "password";
	public $db_db 				= "database";

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