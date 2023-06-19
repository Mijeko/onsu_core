//var initialCost;

$(function(){
	//initialCost = $('tr.total-of-total span.total-price2').text().replace(/\D+/,'');
	$('section.ys-delivery input').on('change', function () {
		setDelivery(initialCost);
	});

	$('#basket-delete-all').on('click', function(e){
		e.preventDefault();
		$('#popup_basket .popup-footer .btn-delete').click();
		$('#basket_no_items_info').show().siblings().remove();
		$('.form-order').hide();
	});

	var bigBasketTimerQuantity,
	    bigBasketTimerTimeout,
	    bigBasketUpdateTimeout;
	$('#basket_form')
		.on('mousedown', 'button.quantity-change', function(e){
			clearTimeout(bigBasketUpdateTimeout);

			e = e || window.event;
			var _ = $(this);
			if ( _.hasClass('disabled') ) return;

			var id = _.closest('tr').attr('id').slice(4);
			var ratio = parseFloat(_.data('ratio'));
			var sign = _.hasClass('decrease') ? 'down' : 'up';
			var bUseFloatQuantity = $('#quantity_float').val() == "Y" ? true : false;

			setQuantity(id, ratio, sign, bUseFloatQuantity);

			bigBasketTimerTimeout = setTimeout(function(){
				bigBasketTimerQuantity = setInterval(function(){
					setQuantity(id, ratio, sign, bUseFloatQuantity);
				}, 100);
			}, 300);
			_.data('changed', true);
		})
		.on('mouseup mouseleave', 'button.quantity-change', function(e){
			clearTimeout(bigBasketTimerTimeout);
			clearInterval(bigBasketTimerQuantity);
			var _ = $(this);
			if (!_.data('changed')) return;

			_.data('changed', false);
			var basketId = _.closest('tr').attr('id').slice(4);
			var ratio = parseFloat(_.data('ratio'));
			var bUseFloatQuantity = $('#quantity_float').val() == "Y" ? true : false;
			bigBasketUpdateTimeout = setTimeout(function () {
				updateQuantity('QUANTITY_INPUT_' + basketId, basketId, ratio, bUseFloatQuantity);
			}, 300);
		});
	
	setDelivery(initialCost);
	
	var errors = $('div.errortext');
	var text = '';
	for (var i=0; i<errors.length; i++) {
		text += errors.eq(i).text() + "<br><br>";
	}
	if (text.length) {
		RZB2.ajax.showMessage(text, 'fail');
	}
});

function setDelivery(initialCost)
{
	var totalCost;
	var deliveryCost = parseFloat($('section.ys-delivery').find('input:checked').attr('placeholder'), 10);

	if(deliveryCost>0)
	{
		var $tr = $('tr.ys-delivery');
		$tr.css('display', 'table-row');

		var $deliveryTag = $tr.find("span.price");
		$deliveryTag.html(deliveryCost);
		
		totalCost = parseFloat(initialCost, 10) + deliveryCost;
	}
	else
	{
		$('tr.ys-delivery').hide();
		totalCost = initialCost;
	}
	if (typeof BX.Currency == "object") {
		totalCost = BX.Currency.currencyFormat(totalCost, RZB2.ajax.BasketSmall.basketCurrency, false);
	}
	$('tr.total-of-total span.total-price2 .value').html(totalCost);
}

/* new */

function YS_Validate() {
	var $agreement = $('#agreement');
	if (!$agreement.is(':checked')) {
		$.jGrowl($agreement.attr('title'), {life: 7000, theme: 'error', header: BX.message('JGROWL_ERROR')});
	} else {
		$('#calculate').attr('name', 'no_calculate');
		$('#order').attr('name', 'order');
		$('#basket_form').submit();
	}
	return false;
}

function basketDelete(key) {
	$('#popup_basket tr[data-key="' + key + '"] button.btn-delete').trigger('click');
	var $tr = $('#basket_items tr[id="key_' + key + '"]');
	initialCost = parseFloat(initialCost) - parseFloat($tr.find('span.itemSum').text());
	$tr.remove();
	$tr = $('#basket_items tr');
	if ($tr.length < 3) {
		$tr.show();
		$('.form-order').hide();
	}
	setDelivery(initialCost);
}

// used when quantity is changed by clicking on arrows
function setQuantity(basketId, ratio, sign, bUseFloatQuantity)
{
	var curVal = parseFloat(BX("QUANTITY_INPUT_" + basketId).value),
		newVal;

	newVal = (sign == 'up') ? curVal + ratio : curVal - ratio;

	if (newVal < 0)
		newVal = 0;

	if (bUseFloatQuantity)
	{
		newVal = newVal.toFixed(2);
	}

	if (ratio > 0 && newVal < ratio) {
		newVal = ratio;
		BX.addClass(BX('QUANTITY_DOWN_' + basketId), 'disabled');
	} else {
		BX.removeClass(BX('QUANTITY_DOWN_' + basketId), 'disabled');
	}

	if (!bUseFloatQuantity && newVal != newVal.toFixed(2))
	{
		newVal = newVal.toFixed(2);
	}

	newVal = getCorrectRatioQuantity(newVal, ratio, bUseFloatQuantity);

	BX("QUANTITY_INPUT_" + basketId).value = newVal;
	//BX("QUANTITY_INPUT_" + basketId).defaultValue = newVal;

	//updateQuantity('QUANTITY_INPUT_' + basketId, basketId, ratio, bUseFloatQuantity);
}

// check if quantity is valid
// and update values of both controls (text input field for PC and mobile quantity select) simultaneously
function updateQuantity(controlId, basketId, ratio, bUseFloatQuantity)
{
	var oldVal = BX(controlId).defaultValue,
		newVal = parseInt(BX(controlId).value) || 0,
		bIsCorrectQuantityForRatio = false;

	if (ratio === 0 || ratio == 1)
	{
		bIsCorrectQuantityForRatio = true;
	}
	else
	{

		var newValInt = newVal * 10000,
			ratioInt = ratio * 10000,
			reminder = newValInt % ratioInt,
			newValRound = parseInt(newVal);

		if (reminder === 0)
		{
			bIsCorrectQuantityForRatio = true;
		}
	}

	if (bIsCorrectQuantityForRatio)
	{
		if (newVal == oldVal)
		{
			BX(controlId).value = oldVal;
			return;
		}
		BX(controlId).defaultValue = newVal;

		BX("QUANTITY_INPUT_" + basketId).value = newVal;

		// set hidden real quantity value (will be used in actual calculation)
		BX("QUANTITY_" + basketId).value = newVal;
	}
	else
	{
		newVal = getCorrectRatioQuantity(newVal, ratio, bUseFloatQuantity = false);

		if (newVal != oldVal)
		{
			BX("QUANTITY_INPUT_" + basketId).value = newVal;
			BX("QUANTITY_" + basketId).value = newVal;
		}else
		{
			BX(controlId).value = oldVal;
			return;
		}
	}
	//AJAX HERE
	var $basket_form = $('#basket_form');
	var $tr = $basket_form.find('tr[id="key_' + basketId + '"]');
	var params = {
		rz_ajax: 'y',
		action: 'setQuantity',
		id: $tr.attr('data-product'),
		productId: $tr.attr('data-product'),
		quantity: newVal,
		key: basketId
	};
	RZB2.ajax.loader.Start($basket_form, true);
	$basket_form.addClass('deactivated');
	$.post(SITE_DIR + 'ajax/basket.php', params, function(data){
		RZB2.ajax.loader.Stop($('#basket_form'), true);
		$basket_form.queue(function(next){
			$basket_form.removeClass('deactivated');
			next();
		});
		do {
			if (data == null) break;
			data = BX.parseJSON(data);
			if (typeof data != "object") break;
			if (data.STATUS != 'OK') break;
			RZB2.ajax.BasketSmall.Refresh();
			updateBigBasketPrice($tr, newVal, oldVal, basketId);
			return;
		} while (0);
		BX(controlId).value = oldVal;
		BX(controlId).defaultValue = oldVal;
	});
}

function getCorrectRatioQuantity(quantity, ratio, bUseFloatQuantity)
{
	var newValInt = quantity * 10000,
		ratioInt = ratio * 10000,
		reminder = newValInt % ratioInt,
		result = quantity,
		bIsQuantityFloat = false,
		ratio = parseFloat(ratio);


	if (reminder === 0)
	{
		return result;
	}

	if (ratio !== 0 && ratio != 1)
	{
		for (var i = ratio, max = parseFloat(quantity) + parseFloat(ratio); i <= max; i = parseFloat(parseFloat(i) + parseFloat(ratio)).toFixed(2))
		{
			result = i;
		}

	}else if (ratio === 1)
	{
		result = quantity | 0;
	}

	if (parseInt(result) != parseFloat(result))
	{
		bIsQuantityFloat = true;
	}

	result = (bUseFloatQuantity === false && bIsQuantityFloat === false) ? parseInt(result) : parseFloat(result).toFixed(2);

	return result;
}

function updateBigBasketPrice($tr, newVal, oldVal, basketId) {
	newVal = parseInt(newVal);
	oldVal = parseInt(oldVal);
	var unitPrice = parseFloat($tr.find('span.itemCost').text());
	var sumPrice = parseFloat($tr.find('span.itemSum').text());
	var diffPrice = unitPrice * (newVal - oldVal);
	sumPrice += diffPrice;

	var showPrice;
	var currency = 'RUB';
	if(typeof BX.Currency == "object" && currency.length) {
		showPrice = BX.Currency.currencyFormat(sumPrice, currency, false);
	} else {
		showPrice = sumPrice.toFixed(2);
		if (showPrice == Math.round(showPrice)) showPrice = Math.round(showPrice);
	}

	$tr.find('span.itemSum').text(sumPrice);
	$('[id="sum_'+basketId+'"]').text(showPrice);
	initialCost = parseFloat(initialCost) + diffPrice;
	setDelivery(initialCost);
}

function YS_Validate() {
	var $agreement = $('#agreement');
	if (!$agreement.is(':checked')) {
		RZB2.ajax.showMessage($agreement.attr('data-title'), 'fail');
	} else {
		$('#calculate').attr('name', 'no_calculate');
		$('#order').attr('name', 'order');
		$('#basket_form').submit();
	}
	return false;
}
