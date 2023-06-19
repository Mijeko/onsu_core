b2.init.comparePage = function(){
	if (typeof initHorizontalCarousels === 'function') initHorizontalCarousels(document);
	
	function swapCells(tr, prev, target){
		var td1 = tr.children(':eq('+prev+')');
		var td2 = tr.children(':eq('+target+')');
		if (target > prev) {
			td1.detach().insertAfter(td2);
		} else if (target < prev){
			td1.detach().insertBefore(td2);
		}
	}

	// creating fixed column, fixed header and fixed corner
	// DOM manipulations here, so use it wisely:
	// on init
	// on items add/delete
	function createCompareTable(originalTable){
		// cloning original table, deleting id
		var clone = originalTable.clone().removeAttr('id').addClass('clone');
		var tableW = originalTable.outerWidth();
		clone.css('width', tableW);	
		// each fixed part (column, header, corner) has an additional wrap. Reason is:
		// fixed divs has overflow hidden to hide unneeded parts of cloned tables
		// but we need some styled shadows outside them! For those shadows wraps are.

		// creating fixed column and setting its width
		var colWrap = $('<div class="fixed-column-wrap" />');
		var col = $('<div class="fixed-column" />');
		var colW = originalTable.find('th:first-child').outerWidth();
		colWrap.css('width', colW);

		// creating corner
		var cornerWrap = $('<div class="fixed-header-wrap" />');
		var corner = $('<div class="fixed-header fixed-corner" />');

		// creating fixed header
		var headWrap = $('<div class="fixed-header-wrap" />');
		var head = $('<div class="fixed-header" />');
		
		// inserting tables into fixeds and fixeds into their wraps.
		// THIS IS MATRESHKAAA!
		clone.clone().appendTo(col);
		clone.clone().appendTo(corner);
		clone.clone().appendTo(head);
		clone.remove();

		col.find('[id]').removeAttr('id');
		corner.find('[id]').removeAttr('id');
		head.find('[id]').each(function(){
			var _ = $(this);
			_.attr('id', _.attr('id') + '-head');
		});

		corner.appendTo(cornerWrap);
		cornerWrap.appendTo(colWrap);
		col.appendTo(colWrap);
		head.appendTo(headWrap);
		// final step: inserting into DOM!
		colWrap.appendTo(originalTable.closest('.compare-outer-wrapper'));
		headWrap.appendTo(originalTable.closest('.scroll-content'));
		
		// setting height of fixed head and corner. Set it only here, because we need
		// real height of compact head, inserted into DOM just a bit earlier
		var headH = head.find('thead').outerHeight();
		headWrap.css('height', headH);
		cornerWrap.css('height', headH+1); // +1 for border

		// column drag and drop setting here. It should be separate handler, but let it be.
		var tables = $('#compare-table, .fixed-header:not(.fixed-corner) .compare-table');
		function initDrag(){
			tables.on('mousedown taphold', '.drag-handle', function(e){
				e.stopPropagation();
				e.preventDefault();
				var _ = $(this).closest('.compare-item');
				var ghost = _.clone().addClass('ghost');
				
				// getting parent (is it fixed header or whole scroll content?)
				var parent = _.closest('.fixed-header');
				if ( parent.length === 0){
					parent = _.closest('.scroll-content');
				}
				var parentLeft = parent.offset().left;
				
				// positioning ghost above its original
				var left = _.offset().left - parentLeft;
				var w = _.outerWidth();
				ghost.css({'left': left, 'width': w});
				ghost.appendTo(parent);

				// indexes
				var target = _.closest('th').index(),
					prevIndex = target;			

				// getting items for overlap check and tds to highlight
				var items = _.closest('tr').children(),
					highlightedTds = originalTable.find('td:nth-of-type('+target+')'),
					highlightedTds = head.find('td:nth-of-type('+target+')').add(highlightedTds);
					overlap = "50%";
				
				// calculating drag bounds
				var dragLeftBound = parent.find('thead>tr:first-child>th:first-child').outerWidth(),
					dragRightBound = parent.outerWidth() - dragLeftBound,
					dragHeightBound = _.closest('th').outerHeight();

				// smart autoscroll vars
				var scroller = _.closest('.scroller'),
					scrollerOffset = scroller.offset().left,
					scrollLeftBound = scrollerOffset + dragLeftBound,
					scrollRightBound = scrollerOffset + scroller.outerWidth(),
					autoScrollBuffer = 100;

				Draggable.create(ghost, {
					type: 'x',
					lockAxis: true,
					bounds: { left: dragLeftBound, top: 0, width: dragRightBound, height: dragHeightBound},
					edgeResistance: 0.8,
					cursor: "ew-resize",

					onDragStart: function(e){
						items.eq(target).addClass('highlight');
						highlightedTds.addClass('highlight');
					},
					onDrag: function(e){
						// check if ghost overlaps next item
						if ( target < items.length-1 && this.hitTest(items[target+1], overlap) ){
							prevIndex = target;
							target += 1;
						// else check if ghost overlaps prev item
						// target > 1 because items[0] is first column which DON'T contain
						// compare item
						} else if ( target > 1 && this.hitTest(items[target-1], overlap) ){
							prevIndex = target;
							target -= 1;
						}
						
						// check whether target was changed or not
						// if was - switch columns
						if ( prevIndex !== target ){
							// remove old highlighting
							items.eq(prevIndex).removeClass('highlight');
							highlightedTds.removeClass('highlight');

							// swap columns in original table and thead of fixed header
							$('#compare-table, .fixed-header:not(.fixed-corner) thead').find('tr').each(function(){
								swapCells($(this), prevIndex, target);
							});

							items = _.closest('tr').children();
							//^ update order

							// changing current target tds
							highlightedTds = originalTable.find('td:nth-of-type('+target+')');
							highlightedTds = head.find('td:nth-of-type('+target+')').add(highlightedTds);

							// highlighting new target
							items.eq(target).addClass("highlight");
							highlightedTds.addClass("highlight");

							prevIndex = target;
						}

						var cur = this.pointerX;
					  	if (cur > scrollRightBound || (scrollRightBound - cur) < autoScrollBuffer){
					  		scroller.scrollLeft(scroller.scrollLeft()+10);
					  		this.update();
					  	} else if (cur < scrollLeftBound || (cur - scrollLeftBound) < autoScrollBuffer){
					  		scroller.scrollLeft(scroller.scrollLeft()-10);
							this.update();
					  	}
					},/* onDrag */
					onDragEnd: function(e){
						// drag ended, remove highlighting
						items.eq(target).removeClass('highlight');
						highlightedTds.removeClass('highlight');

						// killing Draggable instance and removing ghost completely
						this.kill();
						ghost.remove();
					},
				})
				Draggable.get(ghost).startDrag(e);
			})
		}/*function initDrag*/

		function loadComparePageChunks(){
			require([
				'libs/TweenLite.min',
				'libs/utils/Draggable.min',
				'libs/plugins/CSSPlugin.min'
			], function(){
				initDrag();
			});
		}

		if (windowLoaded) loadComparePageChunks();
		else $(window).load(loadComparePageChunks);
	};

     function checkTop(original, itemHeightCompact, itemHeightFull, offTop){
		var head = original.closest('.compare-outer-wrapper').find('.fixed-header-wrap');
		var curTop = $(window).scrollTop() + offTop;
		var scroll = original.closest('.scroller').find('.scroller__track');
		var stickyHeight = head.height();
		var origHeight = original.height();
		var tableTop = original.offset().top;
		var switchTop = original.find('tbody:nth-of-type(1)').offset().top - stickyHeight;
		if (curTop > switchTop){
			head.addClass('shown');
			if (curTop + stickyHeight + 200 >= tableTop+origHeight){
				head.offset({ top: tableTop+origHeight-stickyHeight-200 });
				scroll.offset({ top: tableTop+origHeight-stickyHeight-200 + itemHeightCompact + 10 })
			} else {
				head.offset({ top: curTop });
				scroll.offset({ top: curTop + itemHeightCompact + 10 });
			}
			
		} else {
			head.removeClass('shown');
			scroll.offset({ top: original.offset().top + itemHeightFull + 3});
		}
	}

	var $scroller = $('.compare-outer-wrapper>.scroller'), scroll;
	$('.compare-outer-wrapper').on('click', '.section-toggle', function(){
		var _ = $(this);
		_.toggleClass('shown');
		var index = _.closest('tbody').index() + 1;
		$('.compare-table').children('tbody:nth-child('+index+')')
				.children('tr:not(.section-header)').toggle();

		if ( !Modernizr.mq('(max-width: 767px)') ){
			if (!$().baron || !$scroller.hasClass('baron_h')) return;
			$scroller.trigger('sizeChange');
		}
		
	})
	if (Modernizr.mq('(max-width: 767px)')){
		return;
	}
	var origTable = $('#compare-table');
	createCompareTable(origTable);

    origTable.parent().find('.fixed-header-wrap .compare-items img.lazy').lazyload({
        effect : "fadeIn",
        threshold : 200,
        failurelimit : 100000
    });

	var scrollMarginTop = 12;
	var itemHCompact = $('.fixed-header:not(.fixed-corner)').find('.compare-item').outerHeight() + scrollMarginTop;
	var itemHFull = origTable.find('.compare-item').outerHeight() + scrollMarginTop;
	var firstColWidth = origTable.find('thead th:first-child').outerWidth();
	
	var offsetTop = 0;

	var hoveredTrs;
	$('.compare-outer-wrapper').on({
		mouseover: function(e){
			var _ = $(this);
			var tbodyIndex = _.closest('tbody').index()+1; //+1 for nth-child
			var trIndex = _.index()+1; // +1 for nth-child
			hoveredTrs = $(e.delegateTarget).find('.compare-table')
				.children(':nth-child('+tbodyIndex+')')
				.children(':nth-child('+trIndex+')').addClass('hovered');
		},
		mouseleave: function(e){
			hoveredTrs && hoveredTrs.length && hoveredTrs.removeClass('hovered');
		}
	}, 'tbody>tr:not(.section-header)');
	
	if ( b2.s.topLinePosition === 'fixed-top' ) offsetTop = 48;
	$body.on('offsetChange.b2comparepage', function(){
		if ( b2.s.topLinePosition === 'fixed-top' ) offsetTop = 48;
		else offsetTop = 0;
		setTimeout(function(){
			checkTop(origTable, itemHCompact, itemHFull, offsetTop);
		}, 100);
	});
	$(window).on('scroll.b2comparepage', function(){
		checkTop(origTable, itemHCompact, itemHFull, offsetTop);
	});
    $(window).on('load.b2comparepage', function(){
        // initialize scroll bar on compare page
        setTimeout(function(){
           checkTop(origTable, itemHCompact, itemHFull, offsetTop);
        }, 350);
    });
	var compareResizeTimeout;
	$(window).on('resize.b2comparepage', function(){
		clearTimeout(compareResizeTimeout);
		compareReizeTimeout = setTimeout(function(){
			checkTop(origTable, itemHCompact, itemHFull, offsetTop);
		}, 350);
	});
}