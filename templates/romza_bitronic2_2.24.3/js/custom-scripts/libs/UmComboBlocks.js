function UmComboBlocks(block, opt){
	var t = this;
	this.mode = (opt) ? ( opt.mode || null ) : null;
	if ( !this.mode ){ this.mode = 'tabs' };
	this.tempMode = this.mode; // tempMode is to store "big" mode value
	// when below breakpoint - where it is always 'full'
	this.hasSpy = (opt) ? ( opt.hasSpy || false ) : false;
	this.bp = (opt) ? ( opt.bp || 0 ) : 0;
	this.onOpen = (opt) ? ( opt.onOpen || function(){} ) : function(){};
	this.defExpand = ( opt ) ? ( opt.defaultExpanded || 0 ) : 0;
	this.linksContainer = block.children('.combo-links');
	var scrollfixWrap = this.linksContainer.children('.links-wrap');
	this.links = block.find('.combo-link');
	this.targets = block.find('.combo-target');
	this.tabs;
	this.full;
	this.scrollspy;
	this.scrollfix;
	
	var bpReached;
	function checkBp(){
		bpReached = Modernizr.mq('(max-width: '+t.bp+'px)');
		if ( bpReached && t.mode !== 'full'){
			t.tempMode = t.mode;
			t.mode = 'full';
			return true;
		} else if ( t.mode !== t.tempMode ){
			t.mode = t.tempMode;
			return true;
		}
		return false;
		// ^ return false if t.mode hasn't changed
	}

	function reset(){
		if (t.tabs){
			if ( t.defExpand !== 'all' ) t.defExpand = t.tabs.getIndex();
			t.tabs.destroy();
			t.tabs = null;
			block.removeClass('tabs');
		}
		if ( t.full){
			t.full.destroy();
			t.full = null;
			block.removeClass('full');
		}
		if ( t.hasSpy && t.scrollspy ){
			t.scrollspy.destroy();
			t.scrollspy = null;
			t.scrollfix && t.scrollfix.destroy();
			t.scrollfix = null;
		} 
	}

	this.initFull = function(){
		reset();
		t.full = new UmAccordeon(t.targets, {
			active: t.defExpand,
			onChange: function(target){
				t.onOpen(target);
				if ( t.hasSpy && t.scrollfix ){
					t.scrollfix.getDims();
					t.scrollfix.update(true);
				}
			}
		});
        block.addClass('full').removeClass('tabs');
		if ( t.hasSpy ){
			t.scrollspy = new UmScrollSpyMenu(t.links, { accordeon: t.full });
			
			function initFix(){
				if (typeof UmScrollFix === 'function'){
					t.scrollfix = new UmScrollFix(scrollfixWrap, t.linksContainer);
				} else setTimeout(initFix, 1000);
			}
			initFix();
		}
	};

	this.initTabs = function(){
		reset();
		t.tabs = new UmTabs(t.links, { onChange: t.onOpen });
		block.addClass('tabs');
	};
	
	this.switchMode = function(mode, check){
		if ( check && mode === t.mode ) return;
		if ( mode === 'tabs'){ t.initTabs(); }
		else { t.initFull(); }
		t.mode = mode;
	}
	checkBp();
	this.switchMode(t.mode);

	function afterResize(){ 
		if ( bpReached !== Modernizr.mq('(max-width: '+t.bp+'px)') ){
			if ( checkBp() ) t.switchMode(t.mode);
		}
		if ( t.full ) t.full.updateContent();
		else t.tabs.updateContent();
	}
	var resizeTimer;
	$(window).on('resize.combo', function(){
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(afterResize, 300);
	})

	this.destroy = function(){
		reset();
		$(window).off('resize.combo');
	}
}