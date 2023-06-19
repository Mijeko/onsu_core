/*
by default each block considered as header + content. So, children().eq(0) is header
and children().eq(1) is content
*/
function UmAccordeon(blocks, opt){
	var t = this;
	this.onChange = (opt) ? ( opt.onChange || function(){} ) : function(){};
	this.active = (opt) ? ( opt.active || 'all' ) : 'all';
	if ( !$.isNumeric(this.active) ) this.active = 'all';
	if ( opt && opt.active === 0 ) this.active = 0; // specific case  :(
	// cache headers and contents
	var headers = $();
	var contents = $();
	blocks.each(function(){
		var children = $(this).children();
		headers = headers.add(children.eq(0));
		contents = contents.add(children.eq(1));
	});

	this.open = function(index, immediate){
		if ( blocks.eq(index).hasClass('shown') ) return;
		if ( immediate){
			contents.eq(index).show();
			blocks.eq(index).addClass('shown');
			t.onChange(contents.eq(index));
		} else {
			contents.eq(index).velocity('slideDown', 'fast', function(){
				blocks.eq(index).addClass('shown');
				t.onChange(contents.eq(index));
			});
		}
	}
	this.close = function(index, immediate){
		if ( immediate ){
			blocks.eq(index).removeClass('shown');
			contents.eq(index).hide();
		} else {
			blocks.eq(index).removeClass('shown');
			contents.eq(index).velocity('slideUp', 'fast', function(){
				t.onChange(contents.eq(index));
			});
		}
	}
	this.toggle = function(index, immediate){
		if ( !blocks.eq(index).hasClass('shown') ) t.open(index);
		else t.close(index);
	}
	this.updateContent = function(){
		blocks.filter('.shown').each(function(){
			var index = $(this).index();
			t.onChange(contents.eq(index));
		})
	}

	if ( t.active === 'all' ){
		blocks.each(function(i){ t.open(i, true); })
	} else {
		blocks.each(function(i){ t.close(i, true); })
		t.open(t.active, true);
	}

	// console.log('set headers handler for ', blocks);
	headers.on('click.umAccordeon', function(){
		var index = headers.index($(this));
		t.toggle(index, false);
	})


	this.destroy = function(){
		blocks.removeClass('shown');
		headers.off('click.umAccordeon');
		contents.css({
			display: '',
		})
	};
}