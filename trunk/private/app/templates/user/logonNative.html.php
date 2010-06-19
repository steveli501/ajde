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
					<h3>Log on!</h3>
					<?php if ($msg = request::getFlash("logon")) {
						echo "<p>$msg</p>";
					} ?>
					<?php $userAuthRequest = new request(array("module" => "user", "action" => "isAuth"));
					if (!$auth = module::load($userAuthRequest)) { ?>
					<form action="/user/requestAuthAndRedir" method="post">						
						<label class="first" for="user">Username:</label>
						<input type="text" size="20" name="username" id="user"></input>
						<label for="pass">Password:</label>
						<input type="password" size="20" name="password" id="pass"></input>
						<input type="hidden" name="redirect" value="<?php echo request::getFlash("redirect"); ?>"></input>
						<input type="submit" name="submit" value="Check me!"></input>
					</form>
					<?php } else { ?>
						<label class="first">Already logged in.<br/>
						Want to <a href="/user/logout">Log out</a>?</label>
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