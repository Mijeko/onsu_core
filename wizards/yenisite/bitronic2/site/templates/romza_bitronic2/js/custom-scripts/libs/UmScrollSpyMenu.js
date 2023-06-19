function UmScrollSpyMenu(links, opt){
	var t = this;
	var body = $('body');
	var container = (opt) ? ( opt.container || $(window) ) : $(window);
	var accordeon = (opt) ? ( opt.accordeon || null ) : null;
	this.offset = ( b2.s.topLinePosition === 'fixed-top' ) ? body.find('.top-line').outerHeight() : 0;

	var containerTop = ( container.offset() ) ? container.offset().top : 0;
	var curTop = container.scrollTop();
	var curMin, curMax;
	var absMin = 0;
	var absMax = body.height();
	this.activeIndex;

	this.activate = function(index){
		// console.log('old index is ', t.activeIndex); //DEBUG

		if ( index !== t.activeIndex ){
			links.filter('.active').removeClass('active');
			// ^ filter instead of .eq(activeIndex) is used because some
			// external actions can lead to active class set on links
			// without changing activeIndex.
			t.activeIndex = index;
			links.eq(t.activeIndex).addClass('active');
		}
		curMin = targets[t.activeIndex].min;
		curMax = targets[t.activeIndex].max;
		// console.log('new index is ', t.activeIndex); //DEBUG
	}
	// check if we're currently inside target. If yes, activate it. (check for if
	// it is already activated is performed inside activate function)
	this.checkTarget = function(target, index){
		// console.log('curTop: ', curTop, 'target.min: ', target.min, 'target.max: ', target.max);
		if ( undefined === target ) return;
		if ( curTop >= target.min && curTop < target.max ){
			t.activate(index);
		}
	}

	function setTargetDims(target){
		var el = $(target.el);
		target.min = el.offset().top - t.offset - containerTop - 10;
		target.max = target.min + el.outerHeight();
	}
	// cache targets
	var targets = [];
	links.removeClass('active');
	links.each(function(i){
		var targetElement = $( $(this).attr('href') );
		var target = { el: targetElement };
		setTargetDims(target);
		targets.push(target);
		t.checkTarget(target, i);
	});
	if ( !curMin && curTop < targets[0].min ){
		t.activeIndex = -1;
		curMin = absMin;
		curMax = targets[0].min;
	} else if ( !curMax && curTop > targets[targets.length-1].max ){
		t.activeIndex = targets.length;
		curMin = targets[targets.length-1].max;
		curMax = absMax;
	}
	
	this.next = function(){
		if ( t.activeIndex === targets.length-1 ){
			curMin = targets[targets.length-1].max;
			curMax = absMax;
			t.activeIndex++;
			return;
		}
		// console.log('switch next from ', t.activeIndex); //DEBUG
		t.checkTarget(targets[t.activeIndex+1], t.activeIndex+1);
	}
	this.prev = function(){
		if ( t.activeIndex === 0 ){
			curMin = absMin;
			curMax = targets[0].min;
			t.activeIndex--;
			return;
		}
		// console.log('switch prev from ', t.activeIndex); //DEBUG
		t.checkTarget(targets[t.activeIndex-1], t.activeIndex-1);
	}
	container.on('scroll.umScrollSpyMenu', function(){
		curTop = container.scrollTop();
		if ( curTop < curMin ){
			t.prev();
		} else if ( curTop >= curMax ){
			t.next();
		}
	})

	links.on('click.umScrollSpyMenu', function(e){
		var _ = $(this);
		if ( accordeon ) accordeon.open(_.index());
		$( _.attr('href') ).velocity('scroll', {
			duration: 300,
			offset: -t.offset,
			complete: function(){ 
				t.activate(_.index());
			}
		})
		return false;
	})

	// update targets min & max in case something changed
	function updateDims(){
		containerTop = ( container.offset() ) ? container.offset().top : 0;
		curTop = container.scrollTop();
		$.each(targets, function(i){
			setTargetDims(targets[i]);
			t.checkTarget(targets[i], i);
		})
	}
	// constant checks if something changed
	var updateTick = setInterval(updateDims, 2000);

	body.on('offsetChange.umScrollSpyMenu', function(){
		t.offset = ( b2.s.topLinePosition === 'fixed-top' ) ? body.find('.top-line').outerHeight() : 0;
		updateDims();
	})

	this.destroy = function(){
		clearInterval(updateTick);
		container.off('scroll.umScrollSpyMenu');
		links.off('click.umScrollSpyMenu');
		body.off('offsetChange.umScrollSpyMenu');
	}
}