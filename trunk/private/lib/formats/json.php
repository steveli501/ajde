<?php
class document_json extends document {
	
	function __construct() {
		if (config::getInstance()->debug === true) {
			$this->addHTTPHeader("Content-type: text/plain; charset=utf-8");
		} else {
			$this->addHTTPHeader("Content-type: text/x-json; charset=utf-8");
		}
		$this->addHTTPHeader("Content-language: $this->lang");
		parent::__construct();
	}
	
	function output_header() {
		foreach ($this->httpheaders as $header) {
			header($header);
		}
	}
	
	function output_body() {		
		echo $this->contents["main"];
	}
	
	function output_footer() {
		
	}
	
}
?>