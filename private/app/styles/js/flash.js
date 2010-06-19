/**
 * Flash messages interface
 */
var flash = {
	
	timer : null,
	
	modal : function(b, p) {
		debug.print("flash.modal [b: " + b + ", p: " + p + "]");
		var modal = $("#modal").hide().stop().css("opacity", 1);
		modal.children("div").html('<span>' + b + '</span> <p>' + p + '</p>');
		flash.center(modal);
		$("#modal-bg").show();
		modal.fadeIn(1500);
	},
	
	modalClose : function() {
		debug.print("flash.modalClose");
		var modal = $("#modal");
		$("#modal-bg").hide();
		modal.children().css("opacity", 0);
		modal.effect('puff', {}, 200, function() { modal.children().css("opacity", 1); });
	},
	
	flash : function(b, p) {
		debug.print("flash.flash [b: " + b + ", p: " + p + "]");
		var fl = $("#flash").hide().stop().css("opacity", 1);
		fl.html('<span>' + b + '</span> <p>' + p + '</p>');		
		flash.center(fl);
		fl.fadeIn(1500, function() {
			fl.children().css("opacity", 0);
			fl.effect('puff', {}, 200);
		});		
	},
	
	center : function(obj) {
		//obj.css('top', parseInt($(document).scrollTop() + ($(window).height() / 2 - obj.height() / 2))); 
		obj.css('top', parseInt($(window).height() / 2 - obj.height() / 2));
		obj.css('left', parseInt($(window).width() / 2 - obj.width() / 2));
	},
	
	message : function(h1, p, cssClass, fixed) {
		debug.print("flash.message [h1: " + h1 + ", p: " + p + ", cssClass: " + cssClass + ", fixed: " + fixed + "]");
		var div = $("body").prepend("<div>").children("div:first").hide().addClass("message" + (fixed ? " fixed" : "")).addClass(cssClass);
		$(div).append("<h1>").children("h1").html(h1);
		$(div).append("<p>").children("p").html(p);
		$("div.message").removeClass('last');
		$("div.message:last").addClass('last');
		setTimeout(function() {
			$(div).slideDown('slow');
		}, 500);
		if (fixed) {
			$("body").css("padding-top", "30px");
			$(document).scroll(function() {
				if ($(this).scrollTop()) {
					$(div).addClass("fade", 500);
				} else {
					$(div).removeClass("fade", 500);
				};
			});
		}
		return div;
	},
	
	fatal : function(h1, p) {
		debug.print("flash.fatal [h1: " + h1 + ", p: " + p + "]");
		this.message(h1, p, 'fatal');
		setTimeout(function() {
			$("body > *:not(div.message)").fadeOut(3000);
			setTimeout(function() {
				$("body").append("<div>").children("div:last").css("text-align", "center").css("margin-top", "100px").append("<img src='/images/crash.jpg' />").hide().fadeIn(3000);
			}, 3500);
		}, 1500);
	},
	
	warning: function(h1, p){
		debug.print("flash.warning [h1: " + h1 + ", p: " + p + "]");
		this.message(h1, p, 'warning');
	},
	
	notice: function(h1, p, fixed){
		debug.print("flash.notice [h1: " + h1 + ", p: " + p + ", fixed: " + fixed + "]");
		this.message(h1, p, 'notice', fixed);
	}
	
};