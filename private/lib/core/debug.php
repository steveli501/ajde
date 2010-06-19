<?php
class debug {
	
	static private $vars;
	
	/**
	 * 
	 * @return debug 
	 */
	static function getInstance() {
		static $instance;
		if (empty($instance)) {
			$instance = new debug();
		}
		return $instance;
	}
	
	static function addVar($var) {
		self::$vars[] = $var;
	}
	
	static function dump($var) {
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}
	
	static function getVars() {
		return self::$vars;
	}
	
}
?>