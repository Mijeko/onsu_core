b2.init.initScrollTo = function(block, controller) {
    var $body = $('body, html');

    $body.on('click', $(controller).selector, function(){
        var $block = $(block);
        $block.length ? 
            $body.animate({
                scrollTop: $block.offset().top - 100
            }) : '';
    });
}