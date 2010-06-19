<?php
class module_theme extends module {
	
	function preload() {
		if (request::get("theme")) {
			$this->setTheme(request::get("theme"));
		}
	}
	
	function setTheme($theme, $persistant = true) {
		prefs::getInstance()->setPref("theme", $theme, $persistant);
	}
	
	function getTheme() {
		return prefs::getInstance()->getPref("theme");
	}
	
	function menu() {
		$this->output();
	}
}
?>