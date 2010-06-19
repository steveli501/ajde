<?php
class document_html extends document {
	
	function __construct() {
		$this->addHTTPHeader("Content-type: text/html; charset=utf-8");
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