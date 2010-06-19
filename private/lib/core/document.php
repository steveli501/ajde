<?php
class document {
	
	public $meta = array();
	public $js = array();
	public $externalJs = array();
	public $css = array();
	public $link = array();
	public $httpheaders = array();
	
	public $contents;
	public $body;
	
	public $title;
	
	/**
	 * @var request
	 */
	public $request;
	
	private static $instance;
	
	const REDIRECT_HOMEPAGE = 1;
	const REDIRECT_REFFERER = 2;
	
	function __construct() {
		return true;
	}
	
	/**
	 * @param request $request
	 * @return document
	 */
	static function createInstance(request $request) {
		$classname = "document_".$request->format;
		if (!class_exists($classname)) {
			error::warning("Format $classname doesn't exist");
			$classname = "document_".config::getInstance()->default["format"];
			$request->format = $classname;			
		}
		self::$instance = new $classname();
		self::$instance->request = $request;
		return self::$instance;
	}
	
	/**
	 * @return document
	 */
	static function getInstance() {
		if (empty(self::$instance)) {
			return false;
		}
		return self::$instance;
	}
	
	static function getRefferer() {
		return $_SERVER['HTTP_REFERER'];
	}
	
	function getRoute() {
		return $this->request->module . "/" . $this->request->action . "/" . $this->request->format;
	}
	
	function getLocation() {
		return $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	}
	
	function setTitle($title) {
		$this->title = $title;
	}
	
	function setRedirect($url = null) {
		if ($url === true || $url == document::REDIRECT_HOMEPAGE) {
			header("Location: /");
		} elseif ($url == document::REDIRECT_REFFERER) {
			header("Location: ".$this->getRefferer());			
		} elseif (substr($url, 0, 7) == "http://") {
			header("Location: $url");
		} elseif ($url) {
			header("Location: /$url");
		} else {
			$self = $_SERVER["PHP_SELF"].($_SERVER["QUERY_STRING"] ? "?" : "").$_SERVER["QUERY_STRING"]; 
			header("Location: $self");
		}
	}
	
	function addCSS($path) {
		$config = config::getInstance();
		if (file_exists($config->site_root."/".$path)) {
			$this->css[] = $path;
		}
	} 
	
	function addJS($path, $atBeginning = false) {
		$config = config::getInstance();
		if (file_exists($config->site_root."/".$path)) {
			if ($atBeginning === true) {
				array_unshift($this->js, $path);
			} else {
				$this->js[] = $path;
			}
		}
	}
	
	function addExternalJS($path) {
		$this->externalJs[] = $path;
	}
	
	function addJQuery() {
		$this->addJS("styles/js/jquery-1.4.2.min.js", true);
	}
	
	function addJQueryUI() {
		$this->addJS("styles/js/jquery-ui-1.7.2.custom.min.js", true);
	}
	
	function addCufon($atBeginning = false) {
		if ($atBeginning) {
			$this->addJS("styles/js/font.js", true);
			$this->addJS("styles/js/cufon.js", true);			
		} else {
			$this->addJS("styles/js/cufon.js", false);
			$this->addJS("styles/js/font.js", false);
		}
	}
	
	function addMeta($content) {
		$this->meta[] = $content;
	}
	
	function addHTTPHeader($header) {
		$this->httpheaders[] = $header;
	}

	function addLink($link) {
		$this->link[] = $link;
	}
	
	function output($part) {
		$functionname = "output_".$part;
		$this->$functionname();
	}
	
	function output_header() {
		return true;
	}
	
	function output_body() {
		return true;
	}
	
	function output_footer() {
		return true;
	}
	
}
?>