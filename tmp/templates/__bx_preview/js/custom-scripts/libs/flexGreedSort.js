(function($){

    $.fn.flexGreedSort = function(action, item, big, last, banner, banner_pos) {
        var that = this, $target = $(this);

        if ( !$target.length ) return;

        if ((typeof item !== 'undefined' && item !== '') && (typeof last !== 'undefined' && last !== '')) {
            var $items, $items_last, $banners, w_target, w_normal, w_line, firstInline, line_tmp, line_sort, voids;

            // adding classes to all variables
            item = '.' + item;
            big = '.' + big;
            banner = '.' + banner;

            $items = $target.find(item);
        } else {
            return;
        }

        if ( typeof banner !== 'undefined' && banner !== '' ) {
            $banners = $target.find(banner);
        }

        this.create = function() {
            if($target.find(item).length <= 0) return false;

            $items_last = $target.find(item + '.' + last);

            w_target = $target.get(0).offsetWidth - 1;
            firstInline = true;
            line_tmp = 1;
            voids = 0;

            if ( typeof big !== 'undefined' && big !== '' ) {
                if ( $target.find(item + big).length ) {
                    w_normal = $target.find(item + ':not(' + big + ')').eq(0).get(0).offsetWidth - 1;
                } else {
                    w_normal = $target.find(item).eq(0).get(0).offsetWidth - 1;
                }
            }

            // calculating the number of the original lines
            for (var i = 0; i < $items.length - 1; i++) {
                if ( firstInline === true ) {
                    w_line = ( $items.eq(i).get(0).offsetWidth - 1 );
                    firstInline = false;
                } else {
                    w_line += ( $items.eq(i).get(0).offsetWidth - 1 );
                }

                if ( !$items.eq(i).hasClass(last) ) {
                    if ( (w_line + $items.eq(i + 1).get(0).offsetWidth - 1) > w_target ) {
                        line_tmp += 1;
                        firstInline = true;

                        // search for voids in the lines of goods
                        if ( (w_line + w_normal) <= w_target ) voids += 1;
                    }
                } else {
                    // search for voids in the lines of goods
                    if ( (w_line + w_normal) <= w_target ) voids += 1;
                }
            }

            line_sort = 1;
            firstInline = true;

            // determining the order of goods in new lines
            for (var i = 0; i < $items.length; i++) {
                if ( !$items.eq(i).hasClass('ordered') ) {
                    if ( firstInline === true ) {
                        w_line = ( $items.eq(i).get(0).offsetWidth - 1 );
                        firstInline = false;
                    } else {
                        w_line += ( $items.eq(i).get(0).offsetWidth - 1 );
                    }

                    $items.eq(i)
                        .css('order', '-' + (line_tmp * 2))
                        .addClass('ordered')
                        .attr('data-sort-order', (line_tmp * 2))
                        .attr('data-sort-line', line_sort);

                    if (i !== $items.length - 1) {
                        var $not_ordered = $target.find(item + ':not(.ordered)').eq(0);

                        if ( $not_ordered.length !== 0) {
                            if ( (w_line + $not_ordered.get(0).offsetWidth - 1) > w_target ) {

                                // transfer of free goods to the emptiness
                                if ( (voids > 0) && (w_line + w_normal) <= w_target ) {
                                    var $items_normal = $target.find(item + ':not(.ordered):not(' + big + ')');

                                    $items_normal.eq(0)
                                        .css('order', '-' + (line_tmp * 2))
                                        .addClass('ordered')
                                        .attr('data-sort-order', (line_tmp * 2))
                                        .attr('data-sort-line', line_sort);
                                }

                                line_tmp -= 1;
                                line_sort += 1;
                                firstInline = true;
                            }
                        }
                    }
                }
            }

            // sorting banners in the sorted goods grid
            if ( typeof banner !== 'undefined' && banner !== '' ) {
                $banners = $target.find(banner);

                //if ( $items_last.length === $banners.length ) {
                    var page_from = 0,
                        page_to;

                    $items_last.each(function(i, el) {
                        page_to = parseInt($(el).attr('data-sort-line'));

                        var tmp = (page_to - page_from) / 2,
                            order;

                        if ( tmp % 2 !== 0 ) {
                            if (banner_pos === 'middle-to-bottom') tmp = Math.floor(tmp) + 1;
                            else if (banner_pos === 'middle-to-top' ) tmp = Math.floor(tmp);
                        }

                        tmp += page_from;
                        order = parseInt($target.find(item + '[data-sort-line="' + tmp + '"]').eq(0).attr('data-sort-order'));

                        $banners.eq(i).css('order', '-' + (order - 1));

                        page_from = page_to;
                    });
                //}
            }
        }

        this.update = function() {
            this.destroy();
            this.create();
        }

        this.destroy = function() {
            $items.each(function() {
                $(this).css('order', '').removeClass('ordered')
                    .removeAttr('data-sort-order').removeAttr('data-sort-line');
            });

            if ( typeof banner !== 'undefined' && banner !== '' ) {
                $banners.each(function() {
                    $(this).css('order', '');
                });
            }
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