b2.init.floatingBuyBlock = function(){
	var buyBlock = $('.product-page .product-main .buy-block-wrap');
	// .product-page to filter out modal
	// .product-main to filter out collection buy-block
	var origin = $('.product-page').find('.buy-block-origin');
	var bbScrollFix;

	function floatBlockReset(){
		if ( bbScrollFix ){
			bbScrollFix.destroy();
			bbScrollFix = null;
		}
	}
	
	function floatBlockInit(){
		if ( !bbScrollFix){
			bbScrollFix = new UmScrollFix(buyBlock, origin, 0, 39);
		}
	}
	
	function buyBlockUpdate(){
		if ( Modernizr.mq('(max-width: 767px)') ) floatBlockReset();
		else floatBlockInit();
	}
	buyBlockUpdate();

	// function to launch after window resize
	function onResizeComplete(){
		buyBlockUpdate();
	}
	// timer for window resize
	var resizeTimeout;
	$(window).resize(function(){
		clearTimeout(resizeTimeout);
		resizeTimeout = setTimeout(onResizeComplete, 300);
	});
}