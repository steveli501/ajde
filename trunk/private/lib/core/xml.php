<?php
class xml {
	
	private $content;
	
	function __construct($filename) {
		$config = config::getInstance();
		$filename = "xml/".$filename;
		if (!file_exists($xmlfile = $config->site_root."/".$filename)) {
			error::warning("XML file '$xmlfile' not found");
		}
		$xml = simplexml_load_file($xmlfile);
		$this->content = $this->convertXMLtoArray($xml);
	}
	
	function getContents() {
		return $this->content;
	}
	
	private function convertXMLtoArray($xml, &$array = array(), $level = 0) {
		static $lastparent;
		foreach($xml as $key => $element) {
			$attr = $element->attributes();
			if (count($element->children())) {
				if ($attr["id"]) {
					$key = (string) $attr["id"];
					$element->addChild("name", $key);
					$element->addChild("parent", $lastparent[$level - 2]);
					$lastparent[$level] = $key;
				}
				$this->convertXMLtoArray($element, $array[$key], $level + 1);
			} elseif(isset($attr["id"])) {
				$array[(string) $attr["id"]] = array();
			} else {
				$array[$key] = (string) $element;
			}
		}
		return $array;
	}
		
}
?>
