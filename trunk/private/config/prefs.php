<?php
class prefs {
	
	private $prefs = array();
	
	function __construct(){
		$this->getUserPrefs();
	}
	
	private function setPrefs($prefs, $persistant = true) {
		if ($persistant) {
			request::set("prefs", $prefs, "cookie");
		}
		$this->prefs = $prefs;
	}
	
	function getPrefs() {
		return $this->prefs;
	}
	
	public function getPref($name) {
		if (isset($this->prefs[$name])) {
			return $this->prefs[$name];
		} else {
			return false;
		}		
	}
	
	public function setPref($name, $value, $persistant = true) {
		$this->setPrefs(array_merge($this->getPrefs(), array($name => $value)), $persistant);
	}
	
	private function getUserPrefs() {
		$this->setPrefs(array_merge(self::getDefaultPrefs(), (array) request::get("prefs", "cookie")));		
	}
		
	private static function getDefaultPrefs() {
		return array(
			"theme" => "default",
			"compress" => config::getInstance()->compress ? "true" : "false"
		);
	}
	
	/**
	 * 
	 * @return prefs 
	 */
	static function getInstance() {
		static $instance;
		if (empty($instance)) {
			$instance = new prefs();
		}
		return $instance;
	}
	
	/**
	 * 
	 * @return prefs
	 */
	static function createInstance() {
		return self::getInstance();
	}
	
}
?>