<?php	
require_once('simpletest/autorun.php');

// Set include path for addFile function
$testPath = $_SERVER['DOCUMENT_ROOT'] . '/test/';
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/';
set_include_path(get_include_path() . PATH_SEPARATOR . $testPath . PATH_SEPARATOR . $rootPath);

// Define paths
define('PRIVATE_DIR', 		'private/');
define('APP_DIR', 			'application/');
define('CONFIG_DIR', 		'config/');
define('LAYOUT_DIR', 		'layout/');
define('TEMPLATE_DIR', 		'template/');
define('MODULE_DIR', 		'modules/');
define('LIB_DIR', 			'lib/');
define('VAR_DIR', 			'var/');
define('CACHE_DIR', 		'cache/');
define('LOG_DIR', 			'log/');
define('PUBLIC_DIR', 		'public/');

// Configure the autoloader
require_once('../' . PRIVATE_DIR.LIB_DIR."Ajde/Core/Autoloader.php");
$dirPrepend = $_SERVER['DOCUMENT_ROOT'] . '/';
Ajde_Core_Autoloader::register($dirPrepend);

class AllTests extends TestSuite {
	
    function __construct() {
    	$this->TestSuite('All tests');
        $this->addFile('core.php');
        $this->addFile('xhtml.php');
    }
    
}