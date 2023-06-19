function whichAnimationEvent(){
    var t,
        el = document.createElement("fakeelement");

    var animations = {
        "animation"      : "animationend",
        "OAnimation"     : "oAnimationEnd",
        "MozAnimation"   : "animationend",
        "WebkitAnimation": "webkitAnimationEnd"
    }

    for (t in animations){
        if (el.style[t] !== undefined){
            return animations[t];
        }
    }
}

var animEnd = whichAnimationEvent();

function initSpecialBlocks(target){
    $(target).find('.special-blocks-carousel, #hurry-carousel').each(function(){
        var lineCount;

        if (!isMobile) {
            if ($(this).hasClass('special-blocks-carousel'))
                lineCount = (b2.s.sbItemsLine) ? b2.s.sbItemsLine : '1';
            if ($(this).hasClass('hurry-carousel'))
                lineCount = (b2.s.hurryItemsLine) ? b2.s.hurryItemsLine : '1';
        } else { lineCount = '1'; }

        var specialSlider = new UmSlider($(this), {
            responsive: 'auto',
            itemsLine: lineCount,
            centeringSingle: true,
            onChange: function(prev, next){
                var t = this;
                t.animating = true;
                var animClass = "superscale";
                var delayOut = 0;
                var delayIn = 50;
                var interval = 100;
                t.content.css({
                    'height': t.content.get(0).getBoundingClientRect().height,
                    'overflow': 'hidden'
                }).addClass('perspective');
                prev.each(function(){
                    var _ = $(this);
                    var offset = _.position();

                    // without setTimout browser sets position absolute first and THEN
                    // gets .position(), which results in { 0, 0 } coords for all items
                    setTimeout(function(){
                        _.css({
                            position: 'absolute',
                            top: offset.top,
                            left: offset.left,
                            overflow: 'hidden'
                        })
                    }, 0);

                    setTimeout(function(){
                        _.addClass(animClass+'-out').one(animEnd, function(){
                            _.removeClass(animClass+'-out active')
                                .css({
                                    position: '',
                                    top: '',
                                    left: '',
                                    overflow: ''
                                });
                        });
                    }, delayOut);
                    delayOut += interval;
                });

                next.each(function(i){
                    var _ = $(this);
                    setTimeout(function(){
                        _.addClass(animClass+'-in active').one(animEnd, function(){
                            _.removeClass(animClass+'-in')
                                .find('.photo-thumbs').sly('reload');

                            if ( i === next.length-1 ){
                                t.animating = false;
                                t.content.css({
                                    height: '',
                                    overflow: ''
                                }).removeClass('perspective');
                                initPhotoThumbs(next);
                            }
                        });
                    }, delayIn);
                    delayIn += interval;
                });
            }/*onChange: function(prev, next){*/
        })/*new UmSlider($(this), { */
        if (specialSlider.inited) b2.el.specialSliders.push(specialSlider);
    })
}