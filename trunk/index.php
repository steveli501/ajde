<?php

// Show errors before errorhandler is initialized in bootstrapping
error_reporting(E_ALL);

if (version_compare(PHP_VERSION, '5.2.3') < 0) {
	die('<h3>Ajde requires PHP/5.1.2 or higher.<br>You are currently running PHP/'.phpversion().'.</h3><p>You should contact your host to see if they can upgrade your version of PHP.</p>');
}

// Now, where can we find the private files?
define('PRIVATE_DIR', 'private/');
define('CLASS_DIR', 'lib/');
define('CONFIG_DIR', 'config/');

require_once(PRIVATE_DIR.CLASS_DIR."Ajde/Core/Bootstrap.php");

$bootstrap = new Ajde_Core_Bootstrap();
$bootstrap->init();

die('end here for now...');

// start session
session_start();

// request
$request = new request($_GET);

// begin document
$doc = document::createInstance($request);

// prefs
prefs::createInstance();

// preload
preloader::getInstance()->run();

// main contents
ob_start();
module::load($request);	
$doc->contents["main"] = ob_get_contents();
ob_end_clean();

// echo document
$doc->output("header");
$doc->output("body");
$doc->output("footer");
	
?>