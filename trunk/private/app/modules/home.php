<?php
Class module_home extends module {

	function preload() {
		$doc = document::getInstance();
		$doc->addLink("rel=\"icon\" type=\"image/png\" href=\"/images/favicon.png\" />");
	}
	
	function __call($name, $arg) {
		$this->output();
	}
}
?>