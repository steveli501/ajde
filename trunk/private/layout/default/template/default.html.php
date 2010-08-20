<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<!--[if IE]><![endif]-->
		<title>Ajde</title>
		<meta name="description" content="">
		<meta name="author" content="">
		<base href="http://<?php echo Config::get('site_root'); ?>" />
		<?php echo Ajde::app()->getDocument()->getHead('css'); ?>
	</head>
	<!--[if lt IE 7 ]> <body class="ie6"> <![endif]-->
	<!--[if IE 7 ]>    <body class="ie7"> <![endif]-->
	<!--[if IE 8 ]>    <body class="ie8"> <![endif]-->
	<!--[if IE 9 ]>    <body class="ie9"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <body> <!--<![endif]-->
		<div id='wrapper'>
			<h1>Ajde</h1>
			<?php echo Ajde::app()->getDocument()->getBody(); ?>
		</div>
		<?php echo Ajde::app()->getDocument()->getScripts(); ?>
	</body>
</html>