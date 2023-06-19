/*
tabs : selector for tabs, which should be <a> tags with href="#someID"
opt : options. Now available:
|-	onChange : function to execute on tab change
*/
function UmTabs(tabs, opt){
	var t = this;
	var $tabs = $(tabs);

	this.onChange = (opt) ? ( opt.onChange || function(){} ) : function(){};
	var targetSelector = (opt) ? ( opt.targetSelector || null ) : null;

	if ( !$tabs.length ){
		return;
	}

	// first step: define active tab and index
	var activeTab = $tabs.filter('.active');
	var activeIndex = activeTab.index();
	if ( activeTab.length > 1 ){
		// there must be only one active tab
		activeTab.removeClass('active');
		activeTab = activeTab.eq(0).addClass('active');
	} else if ( activeTab.length === 0 ){
		// and there must be at least one active tab
		activeTab = $tabs.eq(0).addClass('active');
		activeIndex = 0;
	}

	// cache all targets into jQuery object
	var targets = $();
	$tabs.each(function(i){
		if ( targetSelector ){
			targets = targets.add( $($tabs.eq(i).find(targetSelector).attr('data-target') 
									|| $tabs.eq(i).find(targetSelector).attr('href')) );
		} else {
			targets = targets.add( $($tabs.eq(i).attr('data-target')
									|| $tabs.eq(i).attr('href')) );
		}
	})
	// now hide all targets excluding target of active tab
	targets.not(':eq('+activeIndex+')').hide().removeClass('shown');
	var activeTarget = targets.eq(activeIndex).show().addClass('shown');
	setTimeout(function(){
		t.onChange(activeTarget);
	},0);	

	// tar can be numeric or jQuery object
	this.goTo = function(tar){
		activeTab.removeClass('active');
		oldTarget = activeTarget;
		if ( $.isNumeric(tar) ){
			activeIndex = tar;
			activeTab = $tabs.eq(index).addClass('active');
		} else {
			activeIndex = $tabs.index(tar);
			activeTab = tar.addClass('active');
		}
		activeTarget = targets.eq(activeIndex);
		
		oldTarget.fadeOut(200, function(){
			$(this).removeClass('shown');
			activeTarget.addClass('shown').fadeIn(300, function(){
				t.onChange(activeTarget);
			});
		});
	}

	this.getIndex = function(){
		return activeIndex;
	}
	this.updateContent = function(){
		t.onChange(activeTarget);
	}

	$tabs.on('click.umTabs', function(e){
		var _ = $(this);
		if ( _.is(activeTab) ) return false;
		t.goTo(_);
		
		return false; // to prevent default events
	})

	// clear all we've done excluding active class on tabs
	this.destroy = function(){
		$tabs.off('click.umTabs');
		activeTarget.removeClass('shown');
		targets.css({
			display: '',
		})
	}
}