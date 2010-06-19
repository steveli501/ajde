<?php header('Content-type: text/javascript');

// includes
include_once("config/config.php");
include_once("classes/helpers/db.php");
include_once("classes/helpers/compress.php");

if (config::getInstance()->debug) {
	error_reporting(E_ALL | E_STRICT);
} else {
	function flog($log = null, $var = null) {}
	error_reporting(E_NONE);	
}

$js = array();

foreach ($_GET as $key => $value) {
	$js[] = str_replace("_js", ".js", $key);
}

if ($jscache = compress::getCache(array_unique($js), "js", "cache/js/")) {
	readfile($jscache);
} 
?>