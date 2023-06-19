(function($){

$.fn.flexGreedBannerSort = function(action, item, banner, banner_pos){
	var that = this, $target = $(this);
	var orderingGreed;

	if ( !$target.length ) return;

	if ( typeof item !== undefined && item !== '' ) {
		var $items, $banners, w_target, w_line, firstInline;
		var column, column_count, column_before, column_last;
		var order = [];
	} else {
		return;
	}

	this.create = function() {
		$items = $target.find(item);
		$banners = $target.find(banner);

		function compareOrder(obj1, obj2) {
			var order1, order2;

			if ( $(obj1).css('order') == '') order1 = 0; else order1 = parseInt( $(obj1).css('order') );
			if ( $(obj2).css('order') == '') order2 = 0; else order2 = parseInt( $(obj2).css('order') );

			if ( order1 > order2 ) return 1;
			if ( order1 < order2 ) return -1;
			return 0;
		}

		function eachItems(fn_items, action) {
			if ( action === 'ordering' || action === 'not_ordering' ) {
				var tmp_banner = 0, column_before_tmp = column_before;
			}

			for (var i = 0; i < fn_items.length - 1; i++) {
				if ( action === 'ordering' || action === 'not_ordering' ) {
					if ( column <= column_before_tmp ) {
						if ( action === 'ordering' ) {
							fn_items.eq(i).css({
								'order': ( parseInt(fn_items.eq(i).css('order')) - (($banners.length - tmp_banner) * 2) ).toString()
							}).addClass('js_sorting');
						} else {
							fn_items.eq(i).css({
								'order': '-' + (($banners.length - tmp_banner) * 2)
							});
						}
						order[tmp_banner] = parseInt(fn_items.eq(i).css('order'));
					}
				}

				if ( firstInline === true ) {
					w_line = ( fn_items.eq(i).get(0).offsetWidth - 1 );
					firstInline = false;
				} else {
					w_line += ( fn_items.eq(i).get(0).offsetWidth - 1 );
				}

				if ( action === 'ordering' || action === 'not_ordering' ) {
					if ( column <= column_before_tmp ) {
						if ( (column === column_before_tmp) && ((w_line + fn_items.eq(i + 1).get(0).offsetWidth - 1) > w_target) ) {
							tmp_banner += 1;

							if ( tmp_banner !== ($banners.length - 1) ) {
								column_before_tmp += column_before;
							} else {
								if ( column_count % ($banners.length + 1) === 0 ) {
									column_before_tmp += column_before;
								} else {
									column_before_tmp += column_last;
								}
							}
						}
					}
				}

				if ( (w_line + fn_items.eq(i + 1).get(0).offsetWidth - 1) > w_target ) {
					column += 1;
					firstInline = true;
				}
			}
		}

		if ( $banners.length ) {
			orderingGreed = false;

			$items.each(function() {
				if ( $(this).css('order') !== '0' ) {
					$(this).addClass('js_sorting');

					if ( orderingGreed === false )
						orderingGreed = true;
				}
			});

			w_target = $target.get(0).offsetWidth - 1;

			if ( orderingGreed === true ) {
				var $items_order = $target.find(item + '.js_sorting');
				var $items_normal = $target.find(item + ':not(.js_sorting)');

				// sort the items with a value of "order"
				$items_order.sort( compareOrder );

				// combine all the elements into a common sorted array
				$items_normal.each(function() {
					$items_order.push( $(this).get(0) );
				});
			}

			firstInline = true;
			column = 1;

			if ( orderingGreed === true ) {
				eachItems( $items_order );
			} else {
				eachItems( $items );
			}

			column_count = column;

			if ( column_count > ( $banners.length + 1) ) {
				// column more than banner lines of products

				if ( column_count % ($banners.length + 1) === 0 ) {
					column_before = (column_count / ($banners.length + 1));
				} else {
					var tmp_column;

					if ( ((column_count / ($banners.length + 1)) - Math.floor(column_count / ($banners.length + 1))) < (1 / 2) ) {
						column_before = Math.floor( column_count / ($banners.length + 1) );
					} else {
						column_before = Math.floor( column_count / ($banners.length + 1) ) + 1;
					}

					tmp_column = (column_count - (column_before * ($banners.length - 1)));

					if ( tmp_column % 2 === 1 ) {
						if (typeof banner_pos !== undefined) {
							if (banner_pos === 'middle-to-bottom') {
								// position 1 toward the bottom of list

								column_last = ((tmp_column + 1) / 2);
							} else if (banner_pos === 'middle-to-top') {
								// position for 1 closer to the top of list

								column_last = ((tmp_column - 1) / 2);
							}
						} else {
							column_last = ((tmp_column - 1) / 2);
						}
					}
				}

				firstInline = true;
				column = 1;

				if ( orderingGreed !== true ) {
					// the catalog is not sorted, we do a simple sort - to assign the goods to the banner "order" to "-1"

					eachItems( $items, 'not_ordering' );
				} else {
					// the catalog is sorted, we do a complex sort

					eachItems( $items_order, 'ordering' );
				}

				for (var i = 0; i < $banners.length; i++) {
					$banners.eq(i).css({
						'order': (order[i] + 1).toString()
					});
				}
			} else {
				// place the banner after the list of goods

				for (var i = 0; i < $banners.length; i++) {
					$banners.eq(i).css({
						'order': '1'
					});
				}
			}
		}
	}

	this.update = function() {
		this.destroy();
		this.create();
	}

	this.destroy = function() {
		$target.find(banner).css({
			'order': ''
		});
	}

	function resizeHandle(){
		that.update();
	}

	$(window).on('resize', resizeHandle);

	if ( !action || action === 'create' ) this.create();
	if ( action === 'update' ) this.update();
	if ( action === 'destroy' ) this.destroy();

	return this;
}

}(jQuery))