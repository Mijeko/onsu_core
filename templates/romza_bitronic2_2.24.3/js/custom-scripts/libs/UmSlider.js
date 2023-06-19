function UmSlider(el, options){
	var that = this;
	this.inited = false;
	this.content = el.find('.content:first');
	if ( !this.content || this.content.length === 0) return false;
	var width = this.content.outerWidth();
	var controls = el.find('.controls:first');
	
	this.onChange = options.onChange || function(prev, next){
		prev.removeClass('active');
		next.addClass('active');
	};
	this.infinite = options.infinite || false;
	// controls
	this.dotsWrap = options.dotsWrap || controls.children('.dots');
	this.dots = this.dotsWrap.children('.dot');
	this.prev = options.prev || controls.children('.prev');
	this.next = options.next || controls.children('.next');
	this.numeric = options.numeric || controls.children('.numeric');
	this.centeringSingle = options.centeringSingle || false;

	// items
	this.items = options.items || this.content.children();
	this.count = this.items.length;

	// grouping into pages
	this.groupBy = options.groupBy || 1;
	this.responsive = options.responsive || [{ bp: 0, groupBy: this.groupBy }];
	// responsive: array of objects { bp, groupBy}, where bp stands for "breakpoint".
	// first object is always bp null and contains default items, if other bps fail
	this.getGroupBy = function(){
		if ( that.responsive === 'auto' ){
			var activeItems = that.items.filter('.active');
			if (!activeItems.length) activeItems = that.items.addClass('active');
			if (!activeItems.eq(0).is(':visible')) return that.groupBy;
			var contentWidth = that.content.get(0).getBoundingClientRect().width,
				itemWidth = activeItems.get(0).getBoundingClientRect().width,
				percent = (itemWidth * 100) / contentWidth;
				res = Math.floor(100 / Math.floor(percent));

			return res;
		}
		for ( var i = 0; i < that.responsive.length; i++){
			if ( Modernizr.mq('(max-width: '+that.responsive[i].bp+'px)') ){
				return that.responsive[i].groupBy;
			}
		}
		// if we arrived here, it means that all bp's failed and we should return default.
		return that.responsive[0].groupBy;
	}

	this.changeNumeric = function(){
		var shown = that.groupBy;
		if ( that.activePage === that.pageCount-1 ) shown = that.pages[that.activePage].length;
		if ( shown > 1 ){
			that.numeric.html(that.activePage+1+'-'+shown+' / '+that.count);
		} else {
			that.numeric.html(that.activePage+1+' / '+that.count);
		}
	}

	this.pages = []; // each item of array = another array containing items in that page
	this.pageCount;
	this.active; // active item, not page - will be used in item-navigation someday
	this.activePage;
	this.updatePages = function(){
		that.groupBy = that.getGroupBy();
		that.pages = [];
		for (var i = 0; i < that.items.length; i += that.groupBy){
			if ( (i+that.groupBy) >= that.items.length){
				that.pages.push(that.items.slice(i));
				break;
			}
			that.pages.push(that.items.slice(i, i+that.groupBy));
		}
		that.pageCount = that.pages.length;
		if ( that.pageCount === 1 && that.centeringSingle){
			that.content.addClass('center');
		} else that.content.removeClass('center');
		that.active = that.items.filter('.active').eq(0).index();
		if ( -1 === that.active ){
			// if no active items found, start with 0
			that.active = 0;
		}
		that.activePage = Math.floor( that.active / that.groupBy );
		that.items.removeClass('active');
		if ( that.pageCount > 0 ) that.pages[that.activePage].addClass('active');
		that.updateDots();
		that.updateBtns();
		that.changeNumeric();
	}
	this.updateDots = function(){
		var dotsCount = that.dots.length;
		if ( that.pageCount <= 1 ){
			if ( dotsCount !== 0 ){
				that.dots.remove();
				that.dots = $();
			}
			return;
		}
		if ( that.pageCount > dotsCount ){
			var newDots = [];
			for ( var i = 0; i < that.pageCount - dotsCount; i++ ){
				var newDot = $('<i class="dot" />');
				newDots.push(newDot);
				that.dots = that.dots.add(newDot);
			}
			that.dotsWrap.append(newDots);
		} else if ( that.pageCount < dotsCount ){
			var removed = that.dots.slice(that.pageCount).remove();
			that.dots = that.dots.not(removed);
		}
		that.dots.eq(that.activePage).addClass('active').siblings('.active').removeClass('active');
	}
	this.updateBtns = function(){
		if ( that.pageCount <= 1 ) {
			that.prev.css('display', 'none');
			that.next.css('display', 'none');
			return;
		} else {
			that.prev.css('display', '');
			that.next.css('display', '');
		}
		
		that.prev.removeClass('disabled');
		that.next.removeClass('disabled');
		if ( that.activePage === 0 && !that.infinite){
			that.prev.addClass('disabled');
		}
		if ( that.activePage === that.pageCount-1 && !that.infinite){
			that.next.addClass('disabled');
		}
	}
	this.updatePages();

	this.animating = false;
	// defining prev and next items, start animation
	this.changePage = function(targetPage){
		if ( that.animating ){ return;}
		that.counter = 0; // if cycling - this will start interval over
		that.videoPlaying = false;
		var dir = '>';
		var prevPage = that.activePage;

		// changing active page, checking direction (forward / backward) and define
		// animations accordingly
		switch (targetPage){
			case 'next':
				that.activePage++;
				break;
			case 'prev':
				that.activePage--;
				dir = '<';
				break;
			default:
				if ( $.isNumeric(targetPage) ){
					if ( targetPage < that.activePage){
						dir = '<';
					}
					that.activePage = targetPage;
				} else {
					that.activePage++; // just in case smth go wrong
				}
		}
		
		// checking if went outside limits
		if ( that.activePage >= that.pageCount ) that.activePage = 0;
		if ( that.activePage < 0) that.activePage = that.pageCount - 1;
		that.updateBtns();
		that.changeNumeric();

		// switching active dot
		that.dots.eq(prevPage).removeClass('active');
		that.dots.eq(that.activePage).addClass('active');

		// get prev and next items and pass them to change function with dir
		that.onChange(that.pages[prevPage], that.pages[that.activePage], dir, prevPage, that.activePage);
	}

	// controls functionality
	this.dotsWrap.on('click', '.dot', function(){
		var _ = $(this);
		if ( _.hasClass('active') ) return;
		that.changePage( $(this).index() );
	})
	this.prev.on('click', function(){
		if ( $(this).hasClass('disabled') ) return;
		that.changePage('prev');
	})
	this.next.on('click', function(){
		if ( $(this).hasClass('disabled') ) return;
		that.changePage('next');
	})
	this.content.on('swipeleft', function(){
		if ( Modernizr.mq('(max-width: 767px)') ){
			that.changePage('next');
		}
	})
	this.content.on('swiperight', function(){
		if ( Modernizr.mq('(max-width: 767px)') ){
			that.changePage('prev');
		}
	})
	
	// auto sliding
	this.cycle = options.cycle || false;
	this.paused = false;
	this.videoPlaying = false;
	this.interval = options.interval || 8; // time in seconds
	this.counter = 0;
	var timer;
	if ( this.cycle && this.count > 1){
		timer = setInterval(function(){
			if ( that.paused || that.videoPlaying ) return;
			if ( that.counter++ === that.interval ){
				that.changePage('next');
			}
		}, 1000);
		el.hover(function(){ that.paused = true; }, function(){ that.paused = false; });
	}

	function afterResize(){
		if ( that.groupBy !== that.getGroupBy() ){
			that.updatePages();
		}
	}
	var resizeTimer;
	$(window).on('resize', function(){
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(afterResize, 300);
	})

	this.inited = true;
	el.data('UmSlider', this);
}/*function UMSlider(el, options)*/