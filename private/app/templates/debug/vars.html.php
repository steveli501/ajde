<div id="debug" class="section">
	
	<div id="debug-controls">
		<input type="checkbox" id="debug-show-timer" /><label for="debug-show-timer">show timer events</label>
		<a href="javascript:debug.hide();">sluiten</a>
	</div>
	
	<h1>JavaScript debug</h1>
	
	<div id="js_debug"></div>

	<div id="req_debug">
		
		<h1>Request debug</h1>
	
		<div>
		
			<p>
				<a href="javascript:void(null);" onclick="$('#debug_get').slideToggle();">get</a>
				<a href="javascript:void(null);" onclick="$('#debug_post').slideToggle();">post</a>
				<a href="javascript:void(null);" onclick="$('#debug_session').slideToggle();">session</a>
				<a href="javascript:void(null);" onclick="$('#debug_cookie').slideToggle();">cookie</a>
				|
				<a href="javascript:void(null);" onclick="$('#debug > div > div > div').slideDown();">all</a>
				<a href="javascript:void(null);" onclick="$('#debug > div > div > div').slideUp();">none</a>
			</p>
			
			<?php if (message::getDebugMsg()) {
				html::messageBox(implode("<br/>", message::getDebugMsg()), "Debug", "005300");
			} ?>
			
			<?php if (debug::getVars()) {
				foreach (debug::getVars() as $var) {
					$debugvars[] = var_export($var, true);
				}
				html::messageBox("<pre>".implode("<hr/>", $debugvars)."</pre>", "Vars", "005300");
			} ?>
			
			<div id="debug_get">
				<h3>GET</h3>
				<pre><?php print_r($_GET); ?></pre>
			</div>
			
			<div id="debug_post">
				<h3>POST</h3>
				<pre><?php print_r($_POST); ?></pre>
			</div>
			
			<div id="debug_session">
				<h3>SESSION</h3>
				<pre><?php print_r($_SESSION); ?></pre>
			</div>
			
			<div id="debug_cookie">
				<h3>COOKIE</h3>
				<pre><?php print_r($_COOKIE); ?></pre>
			</div>
			
			<script type="text/javascript">//$('#debug > div > div').slideDown();</script>
		
		</div>
		
	</div>

</div>