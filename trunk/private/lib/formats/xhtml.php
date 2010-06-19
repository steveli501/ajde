<?php
class document_xhtml extends document {
	
	function __construct() {
		$this->addMeta("http-equiv='Content-type' content='application/xhtml+xml; charset=UTF-8'");
		$this->addMeta("name='language' http-equiv='Content-language' content='".config::getInstance()->lang."'");
		$this->addHTTPHeader("Content-type: application/xhtml+xml");
		$this->addHTTPHeader("Content-language: ".config::getInstance()->lang);
		parent::__construct();
	}
	
	function output_header() {
		// load style before headers in order to get css/js right etc...
		ob_start();
		style::load(config::getInstance()->style);
		$this->body = ob_get_contents();
		ob_end_clean();
		$pagetitle = "";
		if (!empty($this->title)) {
			$pagetitle = $this->title . " - ";
		}
		
		foreach (array_unique($this->httpheaders) as $header) {
			header($header);
		} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='<?php echo config::getInstance()->lang; ?>' lang='<?php echo config::getInstance()->lang; ?>'>
<head>
	<title><?php echo $pagetitle . config::getInstance()->sitename; ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
<?php foreach (array_unique($this->meta) as $meta) { ?>
	<meta <?php echo $meta; ?> />
<?php }
// css
if (config::getInstance()->compress === true) {
	if ($csscache = compress::getCache(array_unique($this->css), "css", "cache/css/")) { ?>
	<link rel="stylesheet" type="text/css" href="/<?php echo $csscache; ?>" />
<?php }
} else {
	foreach (array_unique($this->css) as $css) { ?>
	<link rel="stylesheet" type="text/css" href="/<?php echo $css; ?>" />
<?php }
}
// js
if (config::getInstance()->compress === true) {
	if ($jscache = compress::getCache(array_unique($this->js), "js", "cache/js/")) { ?>
	<script type="text/javascript" src="/<?php echo $jscache; ?>"></script>
<?php }
} else {
foreach (array_unique($this->js) as $js) { ?>
	<script type="text/javascript" src="/<?php echo $js; ?>"></script>
<?php }
}
foreach (array_unique($this->externalJs) as $js) { ?>
	<script type="text/javascript" src="<?php echo $js; ?>"></script>
<?php }
foreach (array_unique($this->links) as $link) { ?>
	<link <?php echo $link; ?>/>
<?php } ?>
</head>
	<body id="<?php echo config::getInstance()->ident; ?>">
		<?php
	}
	
	function output_body() {		
		echo $this->body;
	}
	
	function output_footer() {
		?>

</body>
</html>
		<?php
	}
}
?>