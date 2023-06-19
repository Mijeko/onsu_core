function initWidgetSlider() {
    var $wrap = $('.social-boxes'),
        $wrapSlider = $('#widgets-slider-frame'),
        $prev = $wrap.find('.arrow.prev'),
        $next = $wrap.find('.arrow.next'),
        options = {
            horizontal: true,
            itemNav: 'basic',
            activateMiddle: true,
            smart: true,

            mouseDragging: true,
            touchDragging: true,
            releaseSwing: true,
            swingSpeed: 0.2,
            elasticBounds: true,
            interactive: null,
            prevPage: $prev,
            nextPage: $next,
            easing: 'swing',
            keyboardNavBy: 'items',
        },

        sly = new Sly($wrapSlider, options, {
            load: function () {
                if (this.pos.start === this.pos.end) {
                    $wrap.addClass('no-scroll');
                    sly.destroy();
                } else {
                    $wrap.removeClass('no-scroll');
                }
            }
        });
        function hCarouselUpdate() {
            if (sly.initialized) sly.reload();
            else sly.init();
        }

        hCarouselUpdate();
        $(window).on('resize',hCarouselUpdate())

    sly.init();
}