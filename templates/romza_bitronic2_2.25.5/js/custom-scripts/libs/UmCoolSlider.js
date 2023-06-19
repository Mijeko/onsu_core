function UmCoolSlider(cs, opt){
	var t = this,
		slidesWrap = cs.find('.slides'),
		csSlides = slidesWrap.children('.slide'),
		csCtrlItems = cs.find('.ctrl-item'),
		track = cs.find('.track'),
		csBars = csCtrlItems.find('.bar'),
		csActive = csCtrlItems.filter('.active').index(),
		period = period || 8000, // time in ms
		csBarMaxW, startTime, tick, timeout, timePassed, pause, percentTime;
	//======================== METHODS ==================================
	function renderBar(){
		if ( pause ) return;
		timePassed = Date.now() - startTime;
		percentTime = (timePassed / period) * 100;
		if ( percentTime >= 100 ){
			cancelAnimationFrame(tick);
			t.changeTo('next');
		} else {
			tick = requestAnimationFrame(renderBar);
			csBars.get(csActive).style.width = percentTime + '%';
		}
	}
	t.updateHeights = function(){
		slidesWrap.css({ 'height': ''}).removeClass('height-set');
		setTimeout(function(){
			var height = slidesWrap.outerHeight();
			slidesWrap.css({'height': height}).addClass('height-set');
		}, 0);
		
	}
	t.start = function(){
		startTime = ( pause ) ? Date.now() - pause : Date.now();
		pause = 0;
		tick = requestAnimationFrame(renderBar);
	}
	t.stop = function(){ cancelAnimationFrame(tick);}
	t.pause = function(){
		t.stop();
		pause = timePassed;
	}
	t.update = function(){
		if ( csSlides.length < 2 ){
			t.stop();
			cs.addClass('compact');
		} else {
			cs.removeClass('compact');
			csBarMaxW = csCtrlItems.eq(1).position().left - csCtrlItems.eq(0).position().left;
			csCtrlItems.not(':last-child').find('.bar-wrap').css('width', csBarMaxW - 100);
			csCtrlItems.eq(csCtrlItems.length-1).find('.bar-wrap').css('width', track.outerWidth() - 100);
			t.updateHeights();
			t.start();
		}
	}
	t.changeTo = function(target){
		if ( target === csActive ) return;
		var oldIndex = csActive;
		if ( target === 'next' ) csActive++;
		else if ( target === 'prev' ) csActive--;
		else if ( $.isNumeric(target) ) csActive = target;

		if ( csActive < 0 ) csActive = csSlides.length-1;
		else if ( csActive > csSlides.length-1 ) csActive = 0;
		if ( oldIndex === csActive ) return;

		csBars.slice(0, csActive).css('width', '100%');
		csBars.slice(csActive).css('width', '0');

		csCtrlItems.eq(oldIndex).removeClass('active');
		csCtrlItems.eq(csActive).addClass('active');
		
		csSlides.stop(true, true).filter('.active').fadeOut(350, function(){
			$(this).removeClass('active');
			csSlides.eq(csActive).fadeIn(500, function(){
				$(this).addClass('active');
			});
		});

		t.start();
	}
	t.destroy = function(){
		t.stop();
		slidesWrap.css('height', '').removeClass('height-set');
		cs.off('click.UmCoolSlider');
		csSlides.off('mouseenter.UmCoolSlider mouseleave.UmCoolSlider');
		$(window).off('resize.UmCoolSlider');
		t = null;
	}
	//==================================== EVENTS ====================================
	cs.on('click.UmCoolSlider', '.ctrl-item', function(e){
		t.changeTo($(this).index());
	}).on('click.UmCoolSlider', '.ctrl-arrow', function(){
		$(this).hasClass('prev') ? t.changeTo('prev') : t.changeTo('next');
	})
	csSlides.on({
		'mouseenter.UmCoolSlider': t.pause,
		'mouseleave.UmCoolSlider': t.start
	})
	$(window).on('resize.UmCoolSlider', function(){
		if ( Modernizr.mq('(max-width: 991px)') ) return;

		clearTimeout(timeout);
		timeout = setTimeout(t.update,200)
	})
	//==================================== INIT ========================================
	t.update();
}