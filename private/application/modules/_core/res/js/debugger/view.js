$(document).ready(function() {
	var height = 400;
	$('#ajdeDebuggerHeader').mouseover(function() {
		if (parseInt($('#ajdeDebuggerContent').css('height')) < height) {
			$('#ajdeDebuggerContent').animate({height: height + 'px'}, 'fast', function() {
				$('#ajdeDebuggerContent').css({overflowY: 'scroll'});
			});
		}
	});
	$('#ajdeDebuggerHeader').click(hideDebugger);
	$('#ajdeDebugger').dblclick(hideDebugger);
	
	function hideDebugger() {
		if (parseInt($('#ajdeDebuggerContent').css('height')) == height) {
			$('#ajdeDebuggerContent').animate({height: '0'}, 'fast');
		}
	}
})
