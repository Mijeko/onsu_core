function UmScrollFix(el, container, offsetTop, offsetBottom){
	var body = $('body');
	var bodyOffset = ( b2.s.topLinePosition === 'fixed-top' ) ? body.find('.top-line').outerHeight() : 0;
	var self = this;
	offsetTop = ( offsetTop ) ? offsetTop+bodyOffset : bodyOffset;
	offsetBottom = ( offsetBottom ) ? offsetBottom : 0;

	var left, w, top, bottom;	

	this.getDims = function(offTop, offBot){
		offsetTop = ( typeof offTop !== 'undefined' ) ? offTop+bodyOffset : offsetTop;
		offsetBottom = ( typeof offBot !== 'undefined' ) ? offBot : offsetBottom;
		var containerOffset = container.offset();
		left = containerOffset.left;
		w = container.outerWidth();
		top = containerOffset.top - offsetTop;
		bottom = top + container.outerHeight() - el.outerHeight() - offsetBottom;
	}
	self.getDims();

	var win = $(window);
	var state;
	var scrollPos;
	this.update = function(forceUpdate){
		scrollPos = win.scrollTop();
		var oldState = state;
		if ( scrollPos <= top ){
			if ( state !== 'normal' || forceUpdate){
				el.css({
					position: '',
					left: '',
					width: '',
					top: '',
					bottom: '',
					zIndex: ''
				});
				state = 'normal';
			}
		} else if ( scrollPos >= bottom ){
			if ( state !== 'bottom' || forceUpdate ){
				el.css({
					position: 'absolute',
					left: '0',
					top: 'auto',
					bottom: offsetBottom,
					zIndex: '1'
				});
				state = 'bottom';
			}
		} else {
			if ( state !== 'fixed' || forceUpdate){
				el.css({
					position: 'fixed',
					left: left,
					width: w,
					top: offsetTop,
					bottom: 'auto',
					zIndex: '1'
				});
				state = 'fixed';
			}
		}
		if ( oldState !== state ){
			el.removeClass('state_normal state_fixed state_bottom').addClass('state_'+state);
		}
	}
	self.update();
	
	var v = globalCounter++;
	win.on('scroll.umScrollFix'+v, function(e){
		self.update();
	});
	body.on('offsetChange.umScrollfix'+v, function(){
		bodyOffset = ( b2.s.topLinePosition === 'fixed-top' ) ? body.find('.top-line').outerHeight() : 0;
		self.getDims(0);
		self.update(true);
	})

	// page content size may change eventually (expandables close/open, 
	// tabs/landing mode switches and so on). By setting this update we make
	// scrollfix work always, independently of those changes
	var updating = setInterval(function(){
		self.getDims();
		self.update(true);
	}, 2000);

	function afterResize(){
		self.getDims();
		self.update(true);
	}
	var resizeTimer;
	win.on('resize.umScrollFix'+v, function(){
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(afterResize, 200);
	})

	this.destroy = function(){
		win.off('scroll.umScrollFix'+v);
		win.off('resize.umScrollFix'+v)
		body.off('offsetChange.umScrollFix'+v);
		clearInterval(updating);

		el.css({
			position: '',
			left: '',
			width: '',
			top: '',
			bottom: '',
			zIndex: ''
		}).removeClass('state_normal state_fixed state_bottom');
	}
}