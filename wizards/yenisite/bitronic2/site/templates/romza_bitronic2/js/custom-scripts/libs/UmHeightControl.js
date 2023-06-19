(function($){

$.fn.heightControl = function(action, options){
    return this.each(function(){
        //==VARIABLES
        var that = this, $target = $(this);
        if (!$target.length) return;

        var $link = $target.parent().find('.height-toggle'),
            $content, heightLimit, contentHeight, resizeTimer;
        $content = (options && typeof options.content !== undefined) ? $target.find(options.content) : $target;

        //==METHODS
        this.update = function(){
            if (!$target.length) return;
            $target.css('max-height', '');
            $target.addClass('minified');

            // if (Modernizr.mq('(max-width: 767px)')){
            //  this.destroy();
            //  return;
            // }

            heightLimit = parseInt($target.css('max-height')) + parseInt($target.css('padding-top')) + parseInt($target.css('padding-bottom'))
                || $target.parent().outerHeight();
            contentHeight = $content.get(0).scrollHeight;
            // console.log('scrollHeight', contentHeight, 'heightLimit', heightLimit);

            if ( contentHeight - heightLimit > 0 ){
                $target.addClass('minified expandable').css('max-height', heightLimit);
                $link.show();
            } else {
                $target.removeClass('minified expandable')
                $link.hide();
            }

            $link.off('click.heightControl').on('click.heightControl', function(e){
                if (!$target.length) return false;
                if ( $target.hasClass('minified') ){
                    $target.removeClass('minified').css({
                        'max-height': contentHeight
                    });
                } else {
                    $target.addClass('minified').css({
                        'max-height': heightLimit
                    });
                }
                return false;
            });
        }

        this.destroy = function(){
            $link.off('click.heightControl').hide();
            $target.css('max-height', '').removeClass('minified');
        }

        function resizeHandle(){
            clearTimeout(resizeTimer);

            resizeTimer = setTimeout(function(){
                that.update();
                $target.removeClass('minified').css('max-height',$target.get(0).scrollHeight);
            }, 250);
        }
        //==EVENTS

        //$(window).on('resize', resizeHandle);
        //==ACTION
        if (!action || action === 'update') this.update();
        if (action === 'destroy') this.destroy();
    });
}

}(jQuery));