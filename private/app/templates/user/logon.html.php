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
				<h2>Present yourself!</h2>								
				<div class="logon right">
					<!-- <h3>Log on!</h3> -->
					<?php if ($msg = request::getFlash("logon")) {
						echo "<p>$msg</p>";
					} ?>
					<?php $userAuthRequest = new request(array("module" => "user", "action" => "isAuth"));
					if (!$auth = module::load($userAuthRequest)) { ?>
						<iframe marginwidth="30" marginheight="30" src="http://vdjo.rpxnow.com/openid/embed?token_url=<?php echo urlencode("http://" . config::getInstance()->site_url . "/user/requestRpxAuthAndRedir" . (request::getFlash("redirect") ? "?redirect=" . request::getFlash("redirect") : "")); ?>" scrolling="no" frameBorder="no" allowtransparency="true" style="width:400px;height:240px"></iframe>
					<?php } else { ?>
						<h3>You're here!</h3>
						<label class="first">Already logged in.<br/>
						Want to <a href="/user/profile">view your profile</a>?</label>
					<?php } ?>
				</div>
				<?php if ($msg = request::getFlash("general")) { ?>
				<p class="msg"><?php echo $msg; ?></p>
				<?php } ?>
				<p>
					Logon here with your account.<br/><br/>
					<b>&bull; Users<br/>
					&bull; Testpanel members<br/>
					&bull; Administrators</b><br/>
					<br/>
					Don't have an account? <a href="/user/register">Create one now!</a>
				</p>
				<div class="clear"></div>
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