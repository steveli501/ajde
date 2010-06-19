<?php
Class style {
	
	static function load($name = "default") {
		$config = config::getInstance();
		if (!file_exists($stylefile = $config->site_root."/styles/$name.php")) {
			die("Style file $stylefile not found");
		}
		
		// add css/js
		document::getInstance()->addCSS("styles/css/".$name.".css");
		document::getInstance()->addJS("styles/js/".$name.".js");
		
		include_once $stylefile;
	}
	
}
?>