b2.init.brandsCarousel = function(){
    $(document).find('.brands-inner').each(function(){
        var $t = $(this),
            $prev = $t.siblings('.prev'),
            $next = $t.siblings('.next'),
            width = $t.outerWidth();

        $t.find('.lazy-sly').each(function() {
            $(this).lazyload({
                effect: "fadeIn",
                threshold: 400,
                failurelimit: 10000
            });
        });

        if ( Modernizr.mq('(max-width: 767px)') ) {
            $t.sly({
                itemNav: 'forceCentered',
                activateMiddle: true,
                prevPage: $prev,
                nextPage: $next
            }, {
                moveEnd: function(eventName) {
                    $t.find('.lazy-sly').lazyload();
                }
            });
        } else {
            $t.sly({
                itemNav: 'basic',
                prevPage: $prev,
                nextPage: $next
            }, {
                moveEnd: function(eventName) {
                    $t.find('.lazy-sly').lazyload();
                }
            });
        }

        var timeout;
        $(window).resize(function(){
            clearTimeout(timeout);
            timeout = setTimeout(function(){
                if ( $t.outerWidth() !== width){
                    width = $t.outerWidth();
                    $t.sly('reload');
                }
            }, 200);
        })
    })
}