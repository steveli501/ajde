<?php
class html {
	
	static function messageBox($msg, $title = "Message", $bgcolor = "005588", $color = "white") {
		?>
		<div style="background-color: #<?php echo $bgcolor; ?>; color: <?php echo $color; ?>; padding: 5px; margin: 0px 10px 10px 0px;">
			<b><?php echo $title; ?>:</b><br/>
			<?php echo $msg; ?>
		</div>
		<?php
	}
	
	static function icon($name) {
		return "<img class='icon' src='/images/icons/$name.png' width='10' height='10' alt='Icon: $name' />";
	}
	
	/**
	 * 
	 * @param string $name
	 * @param string $href
	 * @param array $params [optional] Can be anything from $class = null, $caption = null, $icon = null, $onclick = null
	 * @return 
	 */
	static function button($name, $href, $params = array()) {
		$config = config::getInstance();
		$ret = "<a href='$href'";
		$ret .= $params["onclick"] ? " onclick='{$params["onclick"]}'" : "";
		$ret .= $params["class"] ? " class='{$params["class"]}'" : "";
		$ret .= ">";
		if (!$params["icon"] && $config->useicons) {
			$ret .= html::icon($name);
		} elseif ($params["icon"] === false) {
		} elseif ($config->useicons) {
			$ret .= html::icon($params["icon"]);
		}
		$ret .= $params["caption"] ? $params["caption"] : $name;
		$ret .= "</a>";
		return $ret;
	}
	
}
?>