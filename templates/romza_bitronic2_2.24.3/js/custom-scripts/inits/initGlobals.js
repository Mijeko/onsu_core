var domReady = false,
	windowLoaded = false,
	globalCounter = 0,
	winScrollTop = $(window).scrollTop(),
	$body,
	formatRub = wNumb({
		mark: ',',
		thousand: ' ',
	}),
	requireBaseUrl = 'js',
	bs = {}, // for big slider
	resizeTimeout,
    isMobile, isHover, isTouch;

if (typeof b2 === 'undefined') b2 = {};
b2.el = {}; // for global elements.
b2.s = {}; // s = global Settings. Or State. Or s... whatever.
b2.set = {}; // helper functions for setting... settings.
b2.rel = {}; // settings relations
b2.initialS = {};
b2.changedS = {};
b2.temp = {};
b2.init = {};

b2.el.specialSliders = [];
b2.resizeHandlers = [];

bs.dummy = {};
bs.cur = {};
b2.quickViewGenInfoInited = false;

if (typeof SITE_TEMPLATE_PATH != "undefined") {
	requireBaseUrl = SITE_TEMPLATE_PATH + "/" + requireBaseUrl;
}

require.config({
	baseUrl: requireBaseUrl,
	paths: {
		'custom': 'custom-scripts',
		'libs': '3rd-party-libs',
		'util': 'custom-scripts/utils',
		'init': 'custom-scripts/inits',
		'um': 'custom-scripts/libs',
		'async': '3rd-party-libs/async'
	}
});

if (window.jsDebug) {
	require.config({
		urlArgs: Date.now()
	});
}

$(document).ready(function(){
	domReady = true;
	$body = $(document.body);

    isDevice();
});
$(window).load(function(){
	windowLoaded = true;
});

$.extend(Sly.defaults, {
	horizontal:    true, // Switch to horizontal mode.
	itemNav:       'basic',  // Item navigation type. Can be: 'basic', 'centered', 'forceCentered'.
	smart:         true, // Repositions the activated item to help with further navigation.
	activateOn:    'click',  // Activate an item on this event. Can be: 'click', 'mouseenter', ...
	touchDragging: true, // Enable navigation by dragging the SLIDEE with touch events.

	scrollBy:      0,     // Pixels or items to move per one mouse scroll. 0 to disable scrolling.
	scrollTrap:    false, // Don't bubble scrolling when hitting scrolling limits.
	elasticBounds: true,  // Stretch SLIDEE position limits when dragging past FRAME boundaries.
	dragHandle:    true,  // Whether the scrollbar handle should be draggable.
	dynamicHandle: true,  // Scrollbar handle represents the ratio between hidden and visible content.
	clickBar:      true,  // Enable navigation by clicking on scrollbar.
	syncSpeed:     0.5,   // Handle => SLIDEE synchronization speed, where: 1 = instant, 0 = infinite.

	activatePageOn: 'click', // Event used to activate page. Can be: click, mouseenter, ...
	speed:          300,     // Animations speed in milliseconds. 0 to disable animations.

	pageBuilder:             // Page item generator.
		function (index) {
			return '<li></li>';
		},
});

function isDevice() {
    isMobile = Modernizr.mq('(max-width: 767px)');

    if ( Modernizr.touchevents && Modernizr.pointerevents ) isTouch = true;
    else isTouch = false;

    if ( !isTouch && Modernizr.mq('(min-width: 768px)') ) isHover = true;
    else isHover = false;
}

// process all "on resize" functions in one place with delay to ensure
// that resizing is ended.
// How to use: every time you need to do something on resize,
// make this "something" a named function, and then add it like this:
// resizeHandlers.push(something);
// And that's it. You're done.

function resizeDelay(){
    isDevice();

	if (b2.resizeHandlers.length === 0) return;
	clearTimeout(resizeTimeout);
	resizeTimeout = setTimeout(function(){
		for (var i = 0, l = b2.resizeHandlers.length; i < l; i++){
			if (typeof b2.resizeHandlers[i] === 'function'){
				b2.resizeHandlers[i].call();
			}
		}
	}, 300);
}

$(window).on('resize', resizeDelay);