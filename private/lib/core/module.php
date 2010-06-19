<?php
class module {
	
	public $name;
	public $action;
	public $format;
	
	private $_template = null;	
	private $attached;
	
	public function hasTemplate() {
		return !empty($this->_template);
	}
	
	public function getTemplate() {
		return $this->_template;
	}
	
	public function setTemplate($name) {
		$this->_template = $name;
	}
	
	static function get($modulename) {
		$config = config::getInstance();
		if (!file_exists($modulefile = $config->site_root."/modules/$modulename.php")) {
			error::warning("Module file '$modulefile' not found");
			error::http404();
		}
		include_once $modulefile;
		if (!class_exists($classname = "module_$modulename")) {
			error::warning("Module class '$classname' not found");
			error::http404();
		}
		$module = new $classname();
		$module->name = $modulename;
		return $module;
	}
	
	static function load(request $request, $cssclass = null) {
		$config = config::getInstance();
		if (!file_exists($modulefile = $config->site_root."/modules/$request->module.php")) {
			error::warning("Module file '$modulefile' not found");
			error::http404();
		}
		include_once $modulefile;
		if (!class_exists($classname = "module_$request->module")) {
			error::warning("Module class '$classname' not found");
			error::http404();			
		}
		
		// add css/js
		document::getInstance()->addCSS("templates/".$request->module."/css/".$request->action.".css");
		document::getInstance()->addJS("templates/".$request->module."/js/".$request->action.".js");
		
		// execute action
		$module = new $classname();
		$module->name = $request->module;
		$module->action = $request->action;
		$module->format = $request->format;
		
		// container
		if ($cssclass) {
			echo "<div class='$cssclass'>";
		}
		
		if (method_exists($module, $request->action) || method_exists($module, "__call")) {
			$ret = $module->{$request->action}();
		} else {
			error::warning("Module action '$request->action' is not available");
			error::http404();
		}
		
		if ($cssclass) {
			echo "</div>";
		}
		
		return $ret;		
	}
	
	static function loadToPosition(request $request, $position, $cssclass = null) {
		ob_start();
		$ret = module::load($request, $cssclass);
		$doc = document::getInstance();
		$doc->contents[$position] = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
	
	function attach($name, $value) {
		if (is_object($value)) {
			$this->attached[$name] = get_object_vars($value);
		} elseif (is_array($value)) {
			$this->attached[$name] = array();
			foreach($value as $key => $item) {
				if (is_object($item)) {
					$this->attached[$name][$key] = get_object_vars($item);
				} else {
					$this->attached[$name][$key] = $item;
				}
			}
		} elseif (!empty($value)) {
			$this->attached[$name] = $value;
		} else {
			$this->attached[$name] = null;
		}
	}
	
	function output($template = null) {
		if (empty($template) && !$this->hasTemplate()) {
			$this->setTemplate($this->action);
		} else if (!empty($template)) {
			$this->setTemplate($template);
		}
		if ($this->hasTemplate()) {
			$this->{$this->format}();
		} else {
			error::warning("No template specified");
		}
	}
	
	function null() {
		return false;
	}
	
	function page() {
		$this->html();
	}
	
	function xhtml() {
		$this->html();
	}
	
	function html($format = "html") {
		$config = config::getInstance();
		if (!file_exists($templatefile = $config->site_root."/templates/$this->name/{$this->getTemplate()}.$format.php")) {
			error::warning("Template file $templatefile not found");
			error::http404();
		}
		// extract vars
		if (!empty($this->attached)) {
			foreach ($this->attached as $key => $value) {
				$$key = $value;
			}
		}
		include $templatefile;		
	}
	
	function json() {
		if (count($this->attached) > 0) {
			echo json_encode($this->attached);
		} else {			
		}			
	}
	
	function debug() {
		if (count($this->attached) > 0) {
			debug::dump($this->attached);
		}		
	}
	
	function xml() {
		echo "XML output here...";
	}
	
}
?>