<?php

$doc = document::getInstance();
prefs::getInstance()->setPref("theme", "default");

// placeholder debugger
$doc->addJS("styles/js/debug.js");

$doc->addJS("styles/js/flash.js", true);

// add theme specific css & js
$doc->addJS("theme/".prefs::getInstance()->getPref("theme")."/js/theme.js");
$doc->addCSS("theme/".prefs::getInstance()->getPref("theme")."/css/theme.css");	

?>

<noscript>
<div class="message fatal last">
<h1>Javascript error</h1>
<p>Please enable JavaScript to view this website. <a href="http://www.google.com/support/adsense/bin/answer.py?hl=nl&amp;answer=12654">Read how</a>.</p>
</div>
</noscript>

<?php

/**
 * Prefs in JSON
 */

//<script type="text/javascript">
//	var prefs = {
//	<?php echo "\t"; foreach (prefs::getInstance()->getPrefs() as $key => $value) {
//		if ($value == "true" || $value == "false" || is_numeric($value)) {
//			echo $key . ": " . $value . ", ";
//		} else {
//			echo $key . ": '" . $value . "', ";
//		}
//	} ?&gt;
//	};
//&lt;/script>
 
/**
 * Main templates
 */
echo $doc->contents["main"];

/**
 * Debugger
 */
$config = config::getInstance();
if ($config->debug) {
	$req = new request(array("module" => "debug", "action" => "vars"));
	module::loadToPosition($req, "debug", css::FLOAT_CLEAR); 	
	echo $doc->contents["debug"];
} ?>

<div id="flash"></div>
<div id="modal-bg"></div>
<div id="modal"><div></div><a href="javascript:flash.modalClose();">OK</a></div>

<?php if ($_SERVER["HTTP_HOST"] == "www.example.com") { ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-CODE-HERE");
pageTracker._setDomainName(".example.com");
pageTracker._trackPageview();
} catch(err) {}</script>
<?php } ?>