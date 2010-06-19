<?php

$doc = document::getInstance();

$doc->addJQuery();
$doc->addCufon(true);

// placeholder debugger
$doc->addJS("styles/js/debug.js");

$doc->addJS("styles/js/flash.js", true);

$userAuthRequest = new request(array("module" => "user", "action" => "isAdministrator"));
if (!$auth = module::load($userAuthRequest)) {
	request::setFlash(document::getInstance()->request->module.'/'.document::getInstance()->request->action, "redirect");
	request::setFlash("Not logged in or no administrator privileges", "general");
	document::getInstance()->setRedirect("user/logon");
}

?>

<noscript>
<div class="message fatal last">
<h1>Javascript error</h1>
<p>Please enable JavaScript to view this website. <a href="http://www.google.com/support/adsense/bin/answer.py?hl=nl&amp;answer=12654">Read how</a>.</p>
</div>
</noscript>

<h1>Administrator</h1>

<?php
echo $doc->contents["main"];

$config = config::getInstance();
if ($config->debug) {
	$req = new request(array("module" => "debug", "action" => "vars"));
	module::loadToPosition($req, "debug", css::FLOAT_CLEAR); 	
	echo $doc->contents["debug"];
}
?>

<div id="flash"></div>
<div id="modal-bg"></div>
<div id="modal"><div></div><a href="javascript:flash.modalClose();">OK</a></div>

<script type="text/javascript"> Cufon.now(); </script>