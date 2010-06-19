<?php

$doc = document::getInstance();

$doc->addJQuery();
$doc->addCufon();

$doc->addJS("templates/page/js/general.js");

// menu css
$doc->addCSS("templates/page/css/usmenu.css");

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
				<h2>Join the club!</h2>
				<p>
					<iframe src="http://vdjo.rpxnow.com/openid/embed?token_url=<?php echo urlencode("http://" . config::getInstance()->site_url . "/user/registerRpx"); ?>" scrolling="no" frameBorder="no" allowtransparency="true" style="width:400px;height:240px"></iframe>
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