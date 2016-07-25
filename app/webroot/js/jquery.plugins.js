// Plugin Template

(function($){
    $.fn.extend({
        pluginname: function(options) {
			
			var defaults = {
				
			};
			
			var options =  $.extend(defaults, options);
			
            return this.each(function() {
				var o = options;
				var obj = $(this);
				
				//code to be inserted here
             
            });
        }
    });
	
})(jQuery);

// Function: Mask an element / page
jQuery.mask = function(opt) {
	var defaults = {
		id: '#mask',
		parent: 'window',
		text: 'Loading...',
		top: '30%',
		position: false
	};
	
	var o = $.extend(defaults, opt);
	var id = o.id;
	
	if(id==='#mask') {
		id += $('div.mask').length;
	}
	var txt = o.text;
	var p = o.parent;
	var wnd = p=='window';
	var position = wnd ? wnd : o.position;
	var top = o.top;
	var b = 'body';
	var w = wnd ? $(window).width() : $(p).width();
	var h = wnd ? $(b).height() : $(p).height();
	var loader = '<div id="' + id.replace('#', '') + '" class="mask">';
	loader += '<div id="loading-box">';
	loader += '<span class="loader"></span>'; // loader icon
	loader += '<span class="text">' + txt + '</span>';
	loader += '</div></div>';
	/*
	var alertWrapper = $('<div>').attr({id: id, title: o.title}).addClass('alert').addClass(alertTypes[type]);
	var alertIcon = $('<div>').addClass('alert_icon');
	var alertContent = $('<div>').html(o.text).addClass('alert_content');
	alertWrapper.append(alertIcon).append(alertContent);
	*/
	$(wnd ? b : p).prepend(loader);
	$(id).width(w).height(h);
	$(id + ' #loading-box').centerElement({wnd: wnd});
	if(position && top!=false) $(id + ' #loading-box').css('top', top);
	
	return id;
};

jQuery.unmask = function(opt) {
	var defaults = {
		id: '#mask',
		duration: 300,
		callback: function() {},
		callbackBefore: false,
		callbackAfter: true
	};
	
	var o = $.extend(defaults, opt);
	var id = o.id;
	var func = o.callback;
	
	if(o.callbackBefore && func != undefined) {
		func.apply();
	}
	$(id).fadeOut(o.duration, function() {
		$(this).remove();
		if(o.callbackAfter && func != undefined) {
			func.apply();
		}
	});
};

// Function: CenterElement

(function($){
    $.fn.extend({
        centerElement: function(options) {
			var defaults = {
				wnd: true,	// center to window (true) or parent (false)
				h: true,	// to center horizontally
				v: true,	// to center vertically
				top: false
			}
			
			var options =  $.extend(defaults, options);
			
            return this.each(function() {
				var o = options;
				var obj = $(this);
				var wnd = o.wnd;
				var h = o.h;
				var v = o.v;
				var top = o.top;

				if(wnd) {
					obj.css('position', 'fixed');
					if(h) {
						var windowWidth = $(window).width();
						var w = obj.outerWidth();
						obj.css('left', (windowWidth-w)/2);
					}
					if(v) {
						var windowHeight = $(window).height();
						var hh = obj.outerHeight();
						if(top == false) {
							obj.css('top', (windowHeight-hh)/2);
						} else {
							obj.css('top', top);
						}
					}
				}
				else {
					if(obj.parent().css('position') != 'absolute' && obj.parent().css('position') != 'fixed') {
						obj.parent().css('position', 'relative');
					}
					obj.css('position', 'absolute');
					if(h) {
						var pWidth = obj.parent().width();
						var w = obj.outerWidth();
						obj.css('left', (pWidth-w)/2);
					}
					if(v) {
						var pHeight = obj.parent().height();
						var h = obj.outerHeight();
						obj.css('top', (pHeight-h)/2);
					}
				}
            });
        }
    });
})(jQuery);

jQuery.getPageSize = function() {
	var viewportWidth, viewportHeight;
	
	if (window.innerHeight && window.scrollMaxY) {
		viewportWidth = document.body.scrollWidth;
		viewportHeight = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight) {
		// all but explorer mac
		viewportWidth = document.body.scrollWidth;
		viewportHeight = document.body.scrollHeight;
	} else {
		// explorer mac...would also work in explorer 6 strict, mozilla and safari
		viewportWidth = document.body.offsetWidth;
		viewportHeight = document.body.offsetHeight;
	}
	
	return {width: viewportWidth, height: viewportHeight};
};

// Alerts
var alertType = {
	error: 0,
	ok: 1,
	info: 2,
	warn: 3
};

var alertTimer = {};

jQuery.alert = function(opt) {
	var defaults = {
		id: 'alert-' + new Date().getTime(),
		parent: 'body',
		title: '',//i18n.General.textDismiss,
		text: '',
		type: alertType.ok,
		position: 'top',
		css: {},
		autoFadeOut: true,
	};
	
	var o = $.extend(defaults, opt);
	var id = o.id;
	var txt = o.text;
	var p = o.parent;
	var wnd = p=='window';
	var type = o.type;
	var css = o.css;
	var pos = o.position;
	var fadeOut = o.autoFadeOut;
	var Alert = '#'+id;
	
	if(fadeOut) {
		if(alertTimer[Alert] != undefined) {
			clearTimeout(alertTimer[Alert]['timer']);
		}
		alertTimer[Alert] = {};
	}
	if($(Alert).length>0) { // Remove existing alert if same id exists
		$(Alert).stop().remove();
	}
	var alertTypes = ['alert-danger', 'alert-success', 'alert-info', 'alert-warning'];
	var alertWrapper = $('<div>').attr({id: id, title: o.title}).addClass('alert').addClass(alertTypes[type]);
	//var alertIcon = $('<div>').addClass('alert_icon');
	var alertContent = $('<div>').html(o.text).addClass('alert_content');
	alertWrapper/*.append(alertIcon)*/.append(alertContent);
	
	$('body').prepend(alertWrapper);
	var width = $(Alert).width()+2;
	var height = $(Alert).height();
	var fullWidth = $(Alert).outerWidth();
	var fullHeight = $(Alert).outerHeight();
	$(Alert).remove();
	$(p).prepend(alertWrapper);
	if(css['width'] == undefined) {
		$(Alert).width(width);
	}
	
	for(var i in css) { 
		$(Alert).css(i, css[i]);
		if(i=='top' || i=='left' || i=='right' || i=='bottom') {
			pos = false;
		}
	}
	
	if(pos != false && p!='body') {
		var offsetWidth = 0;
		var offsetHeight = 0;
		var pWidth = $(p).width();
		
		offsetHeight = fullHeight + 10;
		offsetWidth = (pWidth-fullWidth) / 2;
		
		if(pos=='top') {
			var parentPosition = $(p).css('position');
			if(parentPosition != 'relative' && parentPosition != 'absolute' && parentPosition != 'fixed') {
				$(p).css('position', 'relative');
			}
			$(Alert).css({top: 0-offsetHeight, left: offsetWidth});
		} else if (pos=='center') {
			$(Alert).centerElement({wnd: false});
		}
	} else if(pos != false) {
		$(Alert).centerElement({top: '25%'});
	}
	
	$(Alert).click(function() {
		if(alertTimer[Alert] != undefined) {
			clearTimeout(alertTimer[Alert]['timer']);
			delete alertTimer[Alert];
		}
		$(this).stop().remove();
	});
	
	$(Alert).fadeIn(500, function() {
		if(fadeOut) {
			alertTimer[Alert]['timer'] = setTimeout(function() {
				$(Alert).fadeOut(2000, function() { $(Alert).remove(); });
			}, 2000);
		}
	});
	
	$(Alert).mouseenter(function() {
		if(fadeOut) {
			clearTimeout(alertTimer[Alert]['timer']);
		}
		$(this).stop().css('opacity', 1);
	});
	
	$(Alert).mouseleave(function() {
		if(fadeOut) {
			alertTimer[Alert]['timer'] = setTimeout(function() {
				$(Alert).fadeOut(2000, function() { $(Alert).remove(); });
			}, 2000);
		}
	});
};
// End Alerts