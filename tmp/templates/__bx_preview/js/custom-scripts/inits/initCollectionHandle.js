b2.init.collectionHandle = function(){
	var target = $('.custom-collection-content>.items-wrap');
	var source = $('.items-to-choose-from>.items-wrap');
	if ( !(target.length > 0 && source.length > 0) ) return;

	target.add(source).each(function(){
		var frame = $(this);
		var $scrollbar = frame.children('.sly-scroll');
		frame.sly({
			horizontal: 1,
			itemNav: 'basic',

			dragSource: $(this).children('.slidee'),
			touchDragging: true,
			elasticBounds: true,

			scrollBar: $scrollbar,
			dragHandle: true,
			dynamicHandle: true,
			minHandleSize: 30,
			clickBar: true,
			syncSpeed: 0.5,

			interactive: '.item',
			speed: 300,
		}).sly('on', 'load', function(){
			// check if there is nowhere to scroll
			if (this.pos.start === this.pos.end) {
				$scrollbar.hide();
			} else {
				$scrollbar.show();
			}
		}).sly('reload');
	});

	function addItem(item){
		var empties = target.find('.item-wrap.empty');
		target.trigger('item.add', item);
		if ( empties.length > 0){
			empties.eq(0).append(item.clone(true)).removeClass('empty');
		} else {
			target.sly('add', '<div class="sign">+</div>');
			target.sly('add', $('<div class="item-wrap"></div>').append( item.clone(true) ).get(0) );
		}
	}
	function removeItem(item){
		var wraps = target.find('.item-wrap');
		target.trigger('item.remove', item);
		if ( wraps.length > 3 ){
			var parent = item.closest('.item-wrap');
			target.sly('remove', parent.prev('.sign') );
			target.sly('remove', parent);
		} else {
			item.closest('.item-wrap').addClass('empty');
			item.remove();
		}
	}
	target.on('mousedown', 'a', function(e){
		e.stopPropagation(); // for links to be possible to click
	});
	source.on('mousedown', 'a', function(e){
		e.stopPropagation(); // for links to be possible to click
	})
	var dragging = 0;
	target.on('mousedown', '.item', function(e){
		var _ = $(this);
		var origX = e.pageX;
		var origY = e.pageY;
		var ready = false;
		var root = _.closest('.item-wrap');
		$(document).on('mousemove.dragCheck', function(e){
			if ( Math.abs(e.pageX - origX) > 5 || Math.abs(e.pageY - origY) > 5 ){
				dragging = 1;
				$(document).off('mousemove.dragCheck');

				var modal = _.closest('.modal-dialog');
				
				var modalPos = modal.offset();
				var pos = _.offset();
				var top = pos.top - modalPos.top;
				var left = pos.left - modalPos.left;
				var h = _.outerHeight();
				var w = _.outerWidth();
				
				var ghost = _.clone().addClass('ghost');
				ghost.css({ 
					top: top,
					left: left,
					height: h,
					width: w
				});
				ghost.appendTo(modal);
				
				Draggable.create(ghost, {
					type: 'x,y',
					edgeResistance: 0.65,
					bounds: modal,
					onDrag: function(){
						if ( !this.hitTest(root, '50%') ){
							ready = true;
							_.addClass('ready-to-remove');
							ghost.addClass('ready-to-remove');
						} else {
							ready = false;
							_.removeClass('ready-to-remove');
							ghost.removeClass('ready-to-remove');
						}
					},
					onDragEnd: function(){
						if ( ready ) {
							removeItem(_);
						}
						ghost.remove();
					}
				});
				Draggable.get(ghost).startDrag(e);
			}
			return false; // to prevent selection of everything
		}).on('mouseup.dragCheck', function(){
			if ( !dragging ){
				// console.log('it was a click');
				removeItem(_);
			} else {
				// console.log('it was a drag');
			}
			dragging = 0;
			$(document).off('mouseup.dragCheck mousemove.dragCheck');
		});
	});
	source.on('mousedown', '.item', function(e){
		var _ = $(this);
		var origX = e.pageX;
		var origY = e.pageY;
		var ready = false;
		$(document).on('mousemove.dragCheck', function(e){
			if ( Math.abs(e.pageX - origX) > 5 || Math.abs(e.pageY - origY) > 5 ){
				dragging = 1;
				$(document).off('mousemove.dragCheck');

				var modal = _.closest('.modal-dialog');
				
				var modalPos = modal.offset();
				var pos = _.offset();
				var top = pos.top - modalPos.top;
				var left = pos.left - modalPos.left;
				var h = _.outerHeight();
				var w = _.outerWidth();
				
				var ghost = _.clone().addClass('ghost to-add');
				ghost.css({ 
					top: top,
					left: left,
					height: h,
					width: w
				});
				ghost.appendTo(modal);
				Draggable.create(ghost, {
					type: 'x,y',
					edgeResistance: 0.65,
					bounds: modal,
					onDrag: function(){
						if ( this.hitTest(target, '50%') ){
							ready = true;
							ghost.addClass('ready-to-add');
							target.sly('toEnd', true);
						} else {
							ready = false;
							ghost.removeClass('ready-to-add');
						}
					},
					onDragEnd: function(){
						if ( ready ) {
							addItem(_);
						}
						ghost.remove();
					}
				});
				Draggable.get(ghost).startDrag(e);
			}
			return false; // to prevent selection of everything
		}).on('mouseup.dragCheck', function(){
			if ( !dragging ){
				// console.log('it was a click');
				addItem(_);
			} else {
				// console.log('it was a drag');
			}
			dragging = 0;
			$(document).off('mouseup.dragCheck mousemove.dragCheck');
		});
	});
}