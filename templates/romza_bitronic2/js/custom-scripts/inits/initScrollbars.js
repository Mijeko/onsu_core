b2.init.scrollbarsTargeted = function(target){
    $(target).find('.scroller_v').baron({
        bar: '.scroller__bar_v',
        barOnCls: 'baron',
        direction: 'v',
    });

    $(target).find('.baron__scroller').each(function(){
        var $t = $(this),
            $bar = $t.children('.scroller__track').children('.scroller__bar');
        if (!$bar.length) $bar = $t.siblings('.scroller__track').children('.scroller__bar');

        // set the number of lines on the height of the filter elements
        if ( typeof $t.data('line') !== 'undefined' && typeof $t.data('line-height') !== 'undefined' ) {
            $t.css({
                'max-height': parseInt($t.data('line') * $t.data('line-height'))
            });
        }

        $t.baron({
            cssGuru: true,
            bar: $bar
        });
    });
}

b2.init.scrollbars = function(){
    if (!$().baron) return;
    b2.init.scrollbarsTargeted(document);

    var $compareScroller = $('.compare-outer-wrapper>.scroller');
    $compareScroller.length && $compareScroller.baron({
        bar: '.scroller__bar_h',
        barOnCls: 'baron_h',
        direction: 'h',
    }).controls({
        track: '.scroller__track'
    });

    var $coolSlider = $('#cool-slider');
    $coolSlider.length && $coolSlider.find('.slider-controls').baron({
        bar: '.scroller__bar_h',
        barOnCls: 'baron_h',
        direction: 'h',
    }).controls({
        track: '.scroller__track'
    });
}