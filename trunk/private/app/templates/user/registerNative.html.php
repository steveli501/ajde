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
				<form action="/user/saveNative" method="post">
				<dl>
					<dt><label for="username">Username:</label></dt>
					<dd>
						<input type="text" size="40" name="username" id="username" value="<?php echo request::getFlash("username"); ?>" />
						<?php if ($msg = request::getFlash("username_error")) { ?>
						<span class="error"><?php echo $msg; ?></span>
						<?php } ?>
					</dd>
					
					<dt><label for="password">Password:</label></dt>
					<dd>
						<input type="password" size="40" name="password" id="password" />
						<?php if ($msg = request::getFlash("password_error")) { ?>
						<span class="error"><?php echo $msg; ?></span>
						<?php } ?>	
					</dd>
					
					<dt><label for="realname">Real name:</label></dt>
					<dd><input type="text" size="40" name="realname" id="realname" value="<?php echo request::getFlash("realname"); ?>" /> (optional)</dd>
					
					<dt><label for="email">E-mail address:</label></dt>
					<dd>
						<input type="text" size="40" name="email" id="email" value="<?php echo request::getFlash("email"); ?>" />
						<?php if ($msg = request::getFlash("email_error")) { ?>
						<span class="error"><?php echo $msg; ?></span>
						<?php } ?>						
					</dd>
					
					<dt></dt>
					<dd><input type="submit" value="Create account"/></dd>
				</dl>
				</form>
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