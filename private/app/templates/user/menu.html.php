<?php
/* @var $modUser module_user */
$modUser = module::get("user"); 
if (!$modUser->isAuth()) { ?>
	<script type="text/javascript">
		var rpxJsHost = (("https:" == document.location.protocol) ? "https://" : "http://static.");
		document.write(unescape("%3Cscript src='" + rpxJsHost +	"rpxnow.com/js/lib/rpx.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
		RPXNOW.overlay = true;
		RPXNOW.language_preference = 'en';
	</script>
	<a class="rpxnow" onclick="return false;"
		href="https://vdjo.rpxnow.com/openid/v2/signin?token_url=<?php echo urlencode("http://" . config::getInstance()->site_url . "/user/requestRpxAuthAndRedir?redirect=http://" . document::getInstance()->getLocation()); ?>">
		log in
	</a> &nbsp; <a href="/register">register</a>
<?php } else { ?>
	<?php if ($modUser->isAdministrator()) { ?>
		<a href="/admin" class="red">admin</a> &nbsp;
	<?php } ?>
	<a href="/profile"><?php echo $modUser->getUsername(); ?></a> &nbsp;
	<a href="/logout">logout</a>
<?php } ?>