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
        var specialSlider = new UmSlider($(this), {
            responsive: 'auto',
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
                        _.css('left', offset.left).addClass('prev');
                    }, 0);

                    setTimeout(function(){
                        _.addClass(animClass+'-out').one(animEnd, function(){
                            _.removeClass(animClass+'-out active prev').css('left', '');
                        });
                    }, delayOut);
                    delayOut += interval;
                });

                next.each(function(i){
                    var _ = $(this);
                    setTimeout(function(){
                        _.addClass(animClass+'-in active').one(animEnd, function(){
                            _.removeClass(animClass+'-in');

                            if ( i === next.length-1 ){
                                t.animating = false;
                                t.content.css({
                                    height: '',
                                    overflow: ''
                                }).removeClass('perspective');

                                if (!isMobile) initPhotoThumbs(next);
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