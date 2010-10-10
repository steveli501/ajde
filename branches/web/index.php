<?php

// Check PHP version
if (version_compare(PHP_VERSION, '5.2.3') < 0) {
	die('<h3>Ajde requires PHP/5.2.3 or higher.<br>You are currently running PHP/'.phpversion().'.</h3><p>You should contact your host to see if they can upgrade your version of PHP.</p>');
}

// Show errors before errorhandler is initialized in bootstrapping
error_reporting(E_ALL);

// Comment to display uncatchable fatal errors
//ini_set('display_errors', 0);

// Try to catch fatal errors
function shutdown()
{
	$traceOn = array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR);
	if ($error = error_get_last()) if (in_array($error['type'], $traceOn))
	{
		$error = new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
		echo Ajde_Exception_Handler::trace($error);
	}
}
register_shutdown_function('shutdown');

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
require_once(PRIVATE_DIR.LIB_DIR."Ajde/Core/Autoloader.php");
Ajde_Core_Autoloader::register();

// Run the main application
$app = Ajde::create();

try {
	$app->run();	
} catch (Ajde_Core_Exception_Deprecated $e) {
	// Uncomment to die on exceptions thrown by deprecated functions / methods
	// (only in debug mode)
	throw $e;
}