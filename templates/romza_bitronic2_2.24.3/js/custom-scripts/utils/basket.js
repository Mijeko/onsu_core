// used in initBuyClick.js
function fly(source, target){
	var tarCoords = target.offset();
	source.each(function(index, element){
		var srcCoords = $(element).offset();
		$(element).clone().css({
			'position': 'absolute',
			'z-index': 100,
			'left': srcCoords.left,
			'top': srcCoords.top,
			'border': '1px solid silver'
		}).appendTo('body').velocity({
			left: tarCoords.left, 
			top: tarCoords.top,
			width: 10,
			height: 10 }, 600, "linear", function(){
				$(this).remove();
		});
	})
}


// from : start value
// to : end value
// target : element where to change numbers
function changePrice(from, to, target, basket){
	var steps = 15;
	var stepInterval = 35;
	var difference = to - from;
	basket.addClass('buzz'); // class for animation (set in CSS)
	basket.css('width', basket.outerWidth()+'px');
	
	var step = difference / steps;
	// we don't need real difference anymore, so use existing variable for setting
	// direction - increase (1) or decrease (-1)
	difference = ( difference > 0 ) ? 1 : -1;
	var interval = setInterval(function(){
		from += step;
		if ( (from - to)*difference >= 0 ){
			// ^ this tricky expression works for both directions
			// we check not against number of steps, but against price
			// shown on current step. If we've reached target, then
			// clearInterval.
			from = to;
			basket.removeClass('buzz').css('width', '');
			clearInterval(interval);
		}
		target.html(formatRub.to( Math.round(from * 100) / 100 ));
	}, stepInterval);
}

// function for visualizing addition of items into basket.
// used in initBuyClick
function addToBasket(basket, addPrice, addItems){
	var basketPriceSpan = basket.find('.basket-total-price>.value');
	var basketPrice = formatRub.from(basketPriceSpan.html());
	
	var basketItemsSpan = basket.find('.basket-items-number');
	var basketItemsSticker = basket.find('.basket-items-number-sticker');
	var basketItems = parseFloat(basketItemsSpan.html().split(' ').join(''));

	var targetPrice = basketPrice + addPrice;
	basketItems += addItems;

	changePrice(basketPrice, targetPrice, basketPriceSpan, basket);
	
	if (basketItems > 10 && basketItems < 15){
		basketItemsSpan.next('span').html('товаров');
	} else {
		if (basketItems % 10 > 1 && basketItems % 10 < 5) basketItemsSpan.next('span').html('товара');
		else{
			if (basketItems % 10 == 1) basketItemsSpan.next('span').html('товар');
			else basketItemsSpan.next('span').html('товаров');
		}
	}
	basketItemsSpan.html(basketItems);
	basketItemsSticker.html(basketItems);
}