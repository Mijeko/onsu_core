function popClose($popup){
    if (typeof $popup === 'undefined' || $popup.length === 0) {
        return false;
    }
    var data = $popup.data();
    var anim = data.anim || 'fade';
    $(document).off('click.popup'+data.eventID).off('keydown.popup'+data.eventID);
    $(window).off('resize.popup'+data.eventID);

    if ( data.toggler !== undefined ) $(data.toggler).removeClass('toggled');
    $popup.velocity('finish').velocity('transition.'+anim+'Out',{
        begin: function(){
            if ( data.darken !== undefined && !$body.data('going-to-dark') ){
                $body.removeClass('darken-popup');
            }
        },
        complete: function(){
            $popup.attr('data-state', 'hidden');
            if ($popup.hasClass('clone')) $popup.remove();
        }


    }).trigger('close');

}

function popOpen($popup, $caller){
    //console.log('open', $popup);
    var counter = globalCounter++,
        data = $popup.data(),
        position = data.position,
        $base = $(data.base),
        anim = data.anim || 'fade';

    var viewport = ($caller && $caller.length) ? $caller.closest('.table-wrap') : $();
    if (viewport.length === 0) viewport = null;
    else{
        $popup = $popup.clone().addClass('clone').appendTo($body);
        viewport = viewport.children('.scroller');
        viewport.one('scroll.popup', function(){
            popClose($popup);
            $(window).off('scroll.popup');
        });
        $(window).one('scroll.popup', function(){
            popClose($popup);
            viewport.off('scroll.popup');
        })
    }

    if (data.container){
        $popup = $popup.clone().addClass('clone').appendTo($(data.container));
        $(window).one('scroll.popup', function(){
            popClose($popup);
        })
    }

    if ( $caller && $caller.length > 0 ){
        var togglerID = $caller.data('toggler');
        var $toggler = $(togglerID);
        if ( togglerID === undefined ){
            if ( $caller.attr('id') === undefined ) $caller.attr('id', 'popup' + counter);
            togglerID = '#' + $caller.attr('id');
            $toggler = $caller;
        }
        $toggler.addClass('toggled');
        $popup.data('toggler', togglerID);
        if ( $base.length === 0 ) $base = $toggler;
        if ( !position ) position = $caller.data('position');
    }

    // if we're dealing with backnav popup
    if ( $caller && $caller.data('backnav') !== undefined ){
        if ( !b2.s.backnavEnabled ){
            window.location.href = $caller.attr('href');
            return true;
        }
        var callerOffset = $caller.position(),
            targetIndex = parseInt($caller.data('backnav')),
            backNavTarget = $popup.find('li').eq(targetIndex),
            offsetTop = backNavTarget.position().top;

        if (offsetTop > parseInt($caller.offset().top - 5))
            offsetTop = parseInt($caller.offset().top - 5);

        backNavTarget.addClass('active').children('a').addClass('toggled');
        $popup.css({
            'top': callerOffset.top,
            'left': callerOffset.left,
            'margin-top': -offsetTop
        }).one('close', function(){
            backNavTarget.off('click.backnav')
                .removeClass('active')
                .children('a').removeClass('toggled');
        });

        backNavTarget.on('click.backnav', function(e){
            popClose($popup);
            return !$(e.target).is('i');
        });
    }

    $( document ).on('click.popup'+counter, function(e){
        // do not close if the modal window is open
        if ( !($(e.target).hasClass('modal')) && !($(e.target).closest('.modal').length) ) {
            // closing on click outside
            if ( !( $(e.target).closest($popup.add($caller)).length > 0 ||
                $popup.hasClass('click-stay')) ){
                //console.log('close via click outside', $popup);
                if ( !($body.find('.modal.in').length) ) {
                    popClose($popup);
                }
            }
        }
    }).on('keydown.popup'+counter, function(e){
        // closing on ESC
        if ( 27 === e.keyCode && !($body.hasClass('modal-open')) ){
            //console.log('close via ESC', $popup);
            popClose($popup);
        }
    });
    $(window).on('resize.popup'+counter, function(){
        if (isHover) popClose($popup);
    })

    $popup.attr('data-state', 'shown').data('eventID', counter)
        .velocity('finish').velocity('transition.'+anim+'In', {
        duration: 500,
        begin: function(){
            $popup.css({
                opacity: 0,
                display: 'block'
            })
            if ( data.darken !== undefined ){
                $body.data('going-to-dark', true).addClass('darken-popup');
            }
            if ( position !== undefined ){
                posPopup($popup, {
                    base: $base,
                    position: position,
                    viewport: viewport
                });
            }
            popHeightUpdate($popup);
        },
        complete: function(){
            // $.ikSelect && $popup.find('select').ikSelect('redraw');

            if ( data.darken !== undefined ){
                $body.addClass('darken-popup').data('going-to-dark', false);
                // ^ adding class again in case something removed it during
                // the process.
            }
        }
    }).trigger('open');
}

function popHeightUpdate($popup){
    var scroller = $popup.children('.table-wrap').children('.scroller');
    if ( !scroller.length ) return;

    scroller.css('height','');
    var popupTop = $popup.offset().top - $(window).scrollTop(),
        popupH = $popup.outerHeight();

    if ( popupTop < 0 ){
        var scrollerH = scroller.outerHeight();
        scroller.css('height', scrollerH + popupTop).trigger('sizeChange');
    } else {
        var windowH = $(window).height(),
            scrollerH = scroller.outerHeight();
        diff = windowH - (popupTop + popupH);
        if ( diff < 0 ) scroller.css('height', scrollerH + diff).trigger('sizeChange');
    }
    scroller.trigger('sizeChange');
}

function parseTarget(target, $el){
    if ( !target || target === '' ) return $el;

    switch ( target[0] ){
        case '>':
            // > : all children
            // >>(someselector) : el.find(someselector)
            // >(someselector) : el.children(someselector)
            if ( 1 === target.length ) return $el.children();
            else if (target[1] === '>') return $el.find(target.substr(2));
            else return $el.children(target.substr(1));
            break;

        case '^':
            // ^ : el.parent()
            // ^(selector)>(selector2) : el.closest(selector).find(selector2)
            // ^(selector)~(selector2) : el.closest(selector).siblings(selector2)
            // ^(selector) : el.closest(selector)
            var index;
            if ( 1 === target.length ) return $el.parent();
            else if ( (index = target.indexOf('>')) !== -1 ){
                var root = target.substr(1, index-1);
                var tar = target.substr(index+1);
                return $el.closest(root).find(tar);

            } else if ( (index = target.indexOf('~')) !== -1 ){
                var root = target.substr(1, index-1);
                var tar = target.substr(index+1);
                return $el.closest(root).siblings(tar);

            } else {
                return $el.closest(target.substr(1));
            }
            break;

        case '#':
            return $(target);
            break;

        case '.':
            return $(target);
            break;

        case '+':
            return $el.next();
            break;

        case '~':
            return $el.siblings(target.substr(1));
            break;

        default:
            console.log('error in parseTarget');
    }
}

function initPopups(){
    if (!Modernizr.mq('(max-width: 767px)')){
        $('[data-state="shown"]').each(function(){
            $(this).removeAttr('data-state');
            popOpen($(this));
        });
    }

    $(document).on('click', '[data-popup]', function(e){
        var $this = $(this);
        var $popup = parseTarget($this.data('popup'), $this);
        if (!$popup.length) return;

        if ( $popup.attr('data-state') !== 'shown' ) popOpen($popup, $this);
        else{
            //console.log('close via toggler', $popup);
            popClose($popup);
        }
        e.preventDefault ? e.preventDefault() : window.event.returnValue = false;
    });
}