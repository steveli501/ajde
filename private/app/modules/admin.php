<?php
Class module_admin extends module {
	
	function __call($name, $arg) {
		config::getInstance()->style = "admin";		
		$this->output();
	}
	
}
?>