$(document).ready(function() {
	$("#debug_get, #debug_post, #debug_session, #debug_cookie").hide();
})

var debug = {
	timer : null,
	print : function(message, milestone) {
		if (config.debugEnabled === false) {
			return;
		}
		if (config.timerExecuting && $("#debug-show-timer:checked").length == 0) { return false; }
		var d = new Date();
		var curr_hour = d.getHours();
		var curr_min = d.getMinutes();
		var milestone = (milestone) ? " class='milestone'" : "";
		if (config.debugConsoleOnly !== true || !(window.console)) {
			$("#js_debug").prepend("<p" + milestone + ">" + curr_hour + ":" + curr_min + " " + message + "</p>");
			this.focus();
		}
		this.console(message);
	},
	console : function(message) {
		if (window.console) {
			console.log(message);
		}
	},
	focus : function() {
		$("#debug").addClass("focus");
		clearTimeout(debug.timer);
		debug.timer = setTimeout(debug.hide, config.debugHide);
	},
	hide : function() {
		$("#debug").slideUp().fadeOut(function() {
			$(this).removeClass("focus").show();
		})
	}
};