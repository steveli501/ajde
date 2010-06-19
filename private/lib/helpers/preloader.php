<?php
class preloader {

	private $modules = array();
	private $cue = array (
		"home",
		"theme",
	);
	
	function run() {
		$ret = array();
		foreach($this->cue as $cue) {
			$req = new request(array("module" => $cue, "action" => "preload", "format" => "null"));
			$ret[] = array($cue => module::load($req));
		}
		$this->modules = $ret;
	}
	
	function getPreloadedModule($name) {
		if (isset($this->modules[$name])) {
			return $this->modules[$name];
		} else {
			return false;
		}
	}
	
	/**
	 * 
	 * @return dispatch 
	 */
	static function getInstance() {
		static $instance;
		if (empty($instance)) {
			$instance = new preloader();
		}
		return $instance;
	}
}
?>