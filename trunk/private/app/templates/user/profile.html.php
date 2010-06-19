<?php

$doc = document::getInstance();

$doc->addJQuery();
$doc->addCufon();

$doc->addJS("templates/page/js/general.js");

// menu css
$doc->addCSS("templates/page/css/usmenu.css");

$userAuthRequest = new request(array("module" => "user", "action" => "isAuth"));
if (!$auth = module::load($userAuthRequest)) {
	request::setFlash(document::getInstance()->request->module.'/'.document::getInstance()->request->action, "redirect");
	document::getInstance()->setRedirect("user/logon");
}

?>

<div id="logo"><a href="/"><img src="/theme/vdjo_default/images/logo.png" width="283" height="97" alt="vdjo.com" /></a></div>
				
<div id="wrapper">
	<div id="wrapper-upper"></div>
	<div id="wrapper-center">
		<div id="contents">
			<div class="wide margin menu">
			<?php $req = new request(
				array("module" => "user", "action" => "youmenu", "format" => "html"));
				module::load($req); ?>
			</div>	
			<div class="wide">
				<h2>My Profile</h2>
				<?php if ($msg = request::getFlash("general")) {
					echo "<p>$msg</p>";
				} ?>
				<p>
					Profile page is coming soon.<br/>
					<a href="/user/logout">Logout</a>
				</p>
			</div>
			<div class="clear"></div>
			<?php $req = new request(
				array("module" => "sitemap", "action" => "show", "format" => "html"));
				module::load($req); ?>	
		</div>
	</div>
	<div id="wrapper-lower"></div>
</div>

<script type="text/javascript"> Cufon.now(); </script>