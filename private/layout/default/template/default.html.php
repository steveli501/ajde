<!DOCTYPE html>
<html>
	<head>
		<title>Ajde</title>
		<base href="http://<?php echo Config::get('site_root'); ?>" />
		<?php echo Ajde::app()->getDocument()->getHead(); ?>
	</head>
	<body>
		<div id='wrapper'>
			<h1>Ajde</h1>
			<?php echo Ajde::app()->getDocument()->getBody(); ?>
		</div>
	</body>
</html>