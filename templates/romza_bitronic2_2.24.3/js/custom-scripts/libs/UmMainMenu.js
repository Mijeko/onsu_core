// Html structure:
/*	.catalog-at-side or .catalog-at-top
 .catalog-menu
 .container
 .btn-catalog-wrap
 .btn-catalog#catalog-show
 .btn-catalog#catalog-hide
 .btn-catalog#catalog-switch
 .catalog-menu-lvl0.main
 .catalog-menu-lvl0-item
 ...
 .catalog-menu-lvl0.additional	<-- inserted by js
 .catalog-menu-lvl0-item
 ...
 */
function MainMenu(menu, visibleItemsNumber){
    var self = this;
    var container = menu.children('.container');
    var main = container.children('.main');
    var mainItems = main.children();
    var mainLinks = mainItems.find('.menu-lvl0-link');

    var btnShow = $('#catalog-show');
    var btnHide = $('#catalog-hide');
    var btnSwitch = $('#catalog-switch');

    var adds = $('<div class="catalog-menu-lvl0 additional" />');
    var addsItems;
    var addsLinks;

    var state;
    var prevState;
    var containerH = container.outerHeight();
    var containerW = container.outerWidth();
    var visible = ( $.isNumeric(visibleItemsNumber) ) ? visibleItemsNumber : 7;
    var stateChanged;
    var visibleChanged;
    var opened = false;

    var timeout;

    this.resetHandlers = function(){
        $(document).off('click.mainMenu resize.mainMenu');
        menu.off('click.menuMobile click.mainMenu mouseenter.mainMenu mouseleave.mainMenu hitstoggle');
        btnSwitch.off('click.mainMenu');
        // console.log('reset handlers'); //DEBUG
    }
    this.resetFull = function(){
        // reset everything - WARNING! DOM HANDLING HERE!
        // console.log('full reset'); //DEBUG
        container.removeClass('ready btn-shown');
        main.css('display', '');
        if ( adds ) adds.detach().css('display','');
        if ( addsItems && addsItems.length > 0){
            mainItems = mainItems.add(addsItems);
            mainLinks = mainLinks.add(addsLinks);
            main.append(addsItems);
        }
        mainItems.css('width', '');
        mainLinks.css('height', '');
        self.resetHandlers();
        //console.log('reset everything');
    };
    this.setHandlersNotMobile = function(){
        menu.on('mouseenter.mainMenu', '.catalog-menu-lvl0-item', function(){
            var _ = $(this);
            var submenu = _.children('.submenu-wrap');
            if ( _.hasClass('opened') ){
                submenu.velocity('stop').velocity('reverse');
                //$('body').addClass('darken');
                return;
            }
            submenu.addClass('opened');

            if (isTouch && !isMobile && b2.s.catalogDarken === 'yes') {
                submenu.parent().find('.menu-lvl0-link').addClass('hovered');

                if (!submenu.closest('.catalog-at-top, .catalog-at-side').hasClass('darken-popup')) {
                    submenu.closest('.catalog-at-top, .catalog-at-side').addClass('darken-popup');
                }
            }

            setTimeout(function(){
                if (typeof initHCarousel === 'function') submenu.find('.scroll-slider-wrap').each(initHCarousel);
            }, 50);
        }).on('mouseleave.mainMenu', '.catalog-menu-lvl0-item', function(){
            var _ = $(this);
            var submenu = _.children('.submenu-wrap');
            submenu.removeClass('opened');

            if (isTouch && !isMobile && b2.s.catalogDarken === 'yes') {
                submenu.closest('.catalog-at-top, .catalog-at-side').removeClass('darken-popup');
                submenu.parent().find('.menu-lvl0-link').removeClass('hovered');
            }
        }).on('hitstoggle', function(e, data){
            if ( data.type === 'show' && typeof initHCarousel === 'function' ){
                data.wrap.each(initHCarousel);
            }
        });
        if (isHover && b2.s.catalogDarken === 'yes') {
            menu.on('mouseenter.mainMenu', '.catalog-menu-lvl0-item .submenu-wrap', function(){
                var _ = $(this);

                _.parent().find('.menu-lvl0-link').addClass('hovered');

                if (!_.closest('.catalog-at-top, .catalog-at-side').hasClass('darken-popup')) {
                    _.closest('.catalog-at-top, .catalog-at-side').addClass('darken-popup');
                }
            }).on('mouseleave.mainMenu', '.catalog-menu-lvl0-item .submenu-wrap', function(){
                var _ = $(this);

                _.closest('.catalog-at-top, .catalog-at-side').removeClass('darken-popup');
                _.parent().find('.menu-lvl0-link').removeClass('hovered');
            });
        }
    }
    this.setMinHandlers = function(){
        btnSwitch.on('click.mainMenu', function(e){
            e.stopPropagation();
            if ( opened ){
                main.velocity('slideUp', 0, 'linear');
                $(this).removeClass('opened');
                opened = false;
            } else {
                main.velocity('slideDown', 0, 'linear');
                $(this).addClass('opened');
                opened = true;
            }
        });
    }
    this.makeTouch = function(){
        menu.on('click', '.menu-lvl0-link', function(e) {
            e.preventDefault();
        });
    }
    this.makeMobile = function(){
        // console.log('make mobile'); //DEBUG
        container.addClass('ready btn-shown');

        menu.on('click.menuMobile', '.menu-lvl0-link', function(e){
            var _ = $(this), submenu;

            if ( _.hasClass('with-addit-link') ) submenu = _.parent().siblings('.submenu-wrap');
            else submenu = _.siblings('.submenu-wrap');
            if ( submenu.length === 0 ) return true;
            _.closest('.container').find('.expanded').not(submenu)
                .velocity('slideUp', 'fast', 'linear').removeClass('expanded');
            if ( submenu.hasClass('expanded') ){
                submenu.velocity('slideUp', 'fast', 'linear').removeClass('expanded');
            } else {
                submenu.velocity('slideDown', 'fast', 'linear').addClass('expanded');
            }
            return false;
        }).on('click.menuMobile', '.menu-lvl1-link', function(e){
            var submenu = $(this).closest('.menu-lvl1-header').siblings('ul');
            if ( submenu.length === 0) return true;
            submenu.closest('.submenu-wrap').find('.expanded').not(submenu)
                .velocity('slideUp', 'fast', 'linear').removeClass('expanded');
            if ( submenu.hasClass('expanded') ){
                submenu.velocity('slideUp', 'fast', 'linear').removeClass('expanded');
            } else {
                submenu.velocity('slideDown', 'fast', 'linear').addClass('expanded');
            }
            return false;
        });
    }
    this.makeSideMin = function(){
        // console.log('make side minified'); //DEBUG
        // close menu on click outside
        if ( menu.closest('.catalog-aside').length === 0 ){
            $(document).on('click.mainMenu', function(e){
                if ( opened && $(e.target).closest('.catalog-menu').length === 0 ){
                    main.velocity('slideUp', 300, 'linear');
                    btnSwitch.removeClass('opened');
                    opened = false;
                }
            });
        } else {
            btnSwitch.addClass('opened');
            opened = true;
            main.css('display', 'block');
            $(document).off('click.mainMenu');
        }
        // here all items must be in main container, so we're done here.
        container.addClass('ready btn-shown');
    }
    this.makeSideFull = function(){
        // console.log('make side full'); //DEBUG
        // here we have vertical menu with some visible items and some hidden.
        // it is available only on home page, left to slider.
        // visible and hidden are defined by measuring overflow outside
        // slider container. Width and height are not set.
        var limit = container.closest('#catalog-at-side').outerHeight();
        // +40 is for padding-bottom which is added on btn-shown
        if ( container.outerHeight() > limit ){
            for (var i = mainItems.length-1; i >=0; i--){
                if ( mainItems.eq(i).position().top+40 <= limit &&
                    i <= visible){
                    addsItems = mainItems.slice(i);
                    addsLinks = mainLinks.slice(i);
                    mainItems = mainItems.slice(0, i);
                    mainLinks = mainLinks.slice(0, i);
                    adds.prepend(addsItems).appendTo(container);

                    break;
                }
            }
            container.addClass('ready btn-shown');
        } else {
            container.addClass('ready');
        }
    };
    this.makeTop = function(){
        // console.log('make top'); //DEBUG
        //console.log('menu: top');
        // here we have horizontal menu with some visible items and
        // (may be) some hidden. Menu is like table: it tries to fit all items
        // but it is impossible, then it will overflow outside the container.
        // Visible and hidden defined first by measuring the overflow, and then
        // by checking agains visible
        var limit = container.width();
        var btnW = 0;
        //console.log('container: ', limit);
        if ( main.outerWidth()>limit || mainItems.length>visible ){
            container.addClass('btn-shown');

            btnW = btnShow.outerWidth();
            limit = container.width() - btnW;
            //console.log('btnW: ', btnW);
            //console.log('limit: ', limit);
            for (var i = mainItems.length-1; i >=0; i--){
                if ( mainItems.eq(i).position().left <= limit && i <= visible){
                    //console.log('item pos.left: ', mainItems.eq(i).position().left);
                    //console.log('i: ', i);
                    addsItems = mainItems.slice(i);
                    addsLinks = mainLinks.slice(i);
                    mainItems = mainItems.slice(0, i);
                    mainLinks = mainLinks.slice(0, i);
                    adds.prepend(addsItems).appendTo(container);
                    //console.log('sliced');
                    break;
                }
            }
        }

        mainItems.css('width', 100/mainItems.length+'%');
        var maxH = main.outerHeight();
        mainLinks.css('height', maxH);

        //console.log('set width');

        container.addClass('ready');

        $(document).on('click.mainMenu', function(e){
            if ( menu.hasClass('opened') &&
                $(e.target).closest('.catalog-menu-lvl0.additional, #btn-catalog-wrap').length === 0){
                menu.removeClass('opened');
            }
        });
    };

    this.switched = function(){
        // console.log('checking switched'); //DEBUG
        if  ( Modernizr.mq('(max-width: 767px)') ){
            if ( state === 'mobile' ){
                // we're on mobiles and state is already mobile, so
                // console.log('on mobiles and state is already mobile, do nothing'); //DEBUG
                return false;
            } else {
                // we've switched from some state to mobile, update values
                prevState = state;
                state = 'mobile';
                // stateChanged = true;
                // console.log('switched from desktop to mobile, update'); //DEBUG
                return true;
            }
        } else {
            if ( state === 'mobile' ){
                // we've switched from mobiles to normal
                // console.log('switched from mobile to desktop, update'); //DEBUG
                if ( prevState ){
                    state = prevState;
                    prevState = 'mobile';
                    if ( state === 'side minified' ){
                        if ( menu.closest('.catalog-page').length > 0 ){
                            if ( Modernizr.mq('(max-width: 991px)') ){
                                $('#catalog-at-side').prependTo('.breadcrumbs');
                            } else {
                                $('#catalog-at-side').prependTo('.catalog-aside');
                            }
                        }
                    }
                    return true;
                } else {
                    self.updateState();
                    return false;
                }
            } else {
                // we haven't switched from/to mobile, so no state changes.
                // but we need to check whether container has changed
                if ( state === 'side full' ){
                    var changed = containerH !== container.outerHeight();
                    // console.log('is container height changed?', changed); //DEBUG
                    return ( changed );
                } else if ( state === 'top' ){
                    var changed = containerW !== container.outerWidth();
                    // console.log('is container width changed?', changed); //DEBUG
                    return ( changed );
                } else if ( state === 'side minified' ){
                    if ( menu.closest('.catalog-page').length > 0 ){
                        if ( Modernizr.mq('(max-width: 991px)') ){
                            $('#catalog-at-side').prependTo('.breadcrumbs');
                        } else {
                            $('#catalog-at-side').prependTo('.catalog-aside');
                        }
                        self.makeSideMin();
                    }
                }
                // console.log('no changes, do nothing'); //DEBUG
                return false;
            }
        }
    }
    this.updateState = function(){
        // this function should be called only on init or on state change from outside
        // console.log('update state called'); //DEBUG
        if ( Modernizr.mq('(max-width: 767px)') ){
            if ( state !== 'mobile' ){
                prevState = state;
                state = 'mobile';
            } else {
                // we're on mobile and trying to switch state through settings.
                // do nothing - especially DON'T CHANGE prevState!
                return;
            }
        } else if ( menu.closest('.catalog-at-top').length > 0 && state !== 'top'){
            prevState = state;
            state = 'top';
        } else if ( menu.closest('.catalog-at-side.full').length > 0 && state !== 'side full'){
            prevState = state;
            state = 'side full';
        } else if ( menu.closest('.catalog-at-side.minified').length > 0 && state !== 'side minified' ){
            prevState = state;
            state = 'side minified';
            if ( menu.closest('.catalog-page').length > 0 ){
                if ( Modernizr.mq('(max-width: 991px)') ){
                    $('#catalog-at-side').prependTo('.breadcrumbs');
                } else {
                    $('#catalog-at-side').prependTo('.catalog-aside');
                }
            }
        }
        self.rebuild(visible);
        // console.log(state); //DEBUG
    }
    this.updateVisible = function(number){
        // check if visible has changed
        if ( visible !== number && $.isNumeric(number) ){
            visible = number;

            if ( state === 'top' ){
                self.rebuild();
            }
        }
    }
    var rebuildTimeout;
    this.rebuild = function(){
        clearTimeout(rebuildTimeout);
        rebuildTimeout = setTimeout(function(){
            // console.log('rebuild called'); //DEBUG
            // timeouts here for browser to calc dimensions after reset
            switch (state){
                case 'mobile':
                    if ( prevState !== 'side minified' ){
                        // prev was either top or side full, need full reset
                        self.resetFull();
                    } else {
                        // if prev is side minified, than we don't need full reset
                        // only reset handlers
                        self.resetHandlers();
                    }
                    clearTimeout(timeout);
                    timeout = setTimeout(function(){
                        self.makeMobile();
                        self.setMinHandlers();
                    }, 10);
                    break;
                case 'side minified':
                    if ( prevState !== 'mobile' ){
                        // prev was either top or side full, need full reset
                        self.resetFull();
                    } else {
                        // if prev is side minified, than we don't need full reset
                        // only reset handlers
                        self.resetHandlers();
                    }
                    clearTimeout(timeout);
                    timeout = setTimeout(function(){
                        self.makeSideMin();
                        self.setHandlersNotMobile();
                        self.setMinHandlers();
                        if ( !isMobile && isTouch ) self.makeTouch();
                    }, 10);
                    break;
                case 'side full':
                    self.resetFull();
                    clearTimeout(timeout);
                    timeout = setTimeout(function(){
                        self.makeSideFull();
                        self.setHandlersNotMobile();
                        menu.on('click.mainMenu', '#catalog-show', function(e){
                            e.stopPropagation();
                            adds.velocity('slideDown', 0, 'linear');
                            menu.addClass('opened');
                        }).on('click.mainMenu', '#catalog-hide', function(e){
                            e.stopPropagation();
                            adds.velocity('slideUp', 0, 'linear');
                            menu.removeClass('opened');
                        });
                        if ( !isMobile && isTouch ) self.makeTouch();
                        containerH = container.outerHeight();
                    }, 10);
                    break;
                case 'top':
                    self.resetFull();
                    clearTimeout(timeout);
                    timeout = setTimeout(function(){
                        self.makeTop();
                        self.setHandlersNotMobile();
                        if ( !isMobile && isTouch ) self.makeTouch();
                        containerW = container.outerWidth();
                        // $('#catalog-at-top').on('click', '#catalog-show', function(e){
                        // 	e.stopPropagation();
                        // 	menu.addClass('opened');
                        // }).on('click', '#catalog-hide', function(e){
                        // 	e.stopPropagation();
                        // 	menu.removeClass('opened');
                        // });
                    }, 10);
                    break;
                default:
                    console.log('Smth wrong with mainMenu.update');
            }

            stateChanged = false;
            visibleChanged = false;
        }, 10);
    }

    //self.updateState();
    //^ commented, because it is called externally, manual mode

    function resizeHandler(){
        if ( self.switched() ){
            self.rebuild();
        }
    }
    var resizeTimeout;
    $(window).on('resize.mainMenu',function(){
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(resizeHandler, 150);
    });
}