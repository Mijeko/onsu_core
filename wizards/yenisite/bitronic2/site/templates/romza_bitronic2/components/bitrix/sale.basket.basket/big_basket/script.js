/**
 * @param basketItemId
 * @param {{BASKET_ID : string, BASKET_DATA : { GRID : { ROWS : {} }}, COLUMNS: {}, PARAMS: {}, DELETE_ORIGINAL : string }} res
 */
function updateBasketTable(basketItemId, res, simpleUpdate)
{
	if(!simpleUpdate)
	{
		//update small basket
		RZB2.ajax.BasketSmall.Refresh(true);
	}
	var _ = $('#basket_form');
	_.html(res);
	new UmTabs('#basket_form .um_tab');

	BX.onCustomEvent('OnBasketChange');
	//RZB2.ajax.loader.Stop($('#basket_items'));
	// gift slider
	if (_.find('.scroll-slider-wrap').length > 0) {
		initHorizontalCarousels(_);
		// trigger event for picturefill
		var event = document.createEvent("HTMLEvents");
		event.initEvent("DOMContentLoaded",true,false);
		window.dispatchEvent(event);
	}
	b2.init.tooltips(_);
	b2.init.selects(_);
	RZB2.utils.initLazy(_);
    b2.init.inputs && b2.init.inputs(_);
}

function skuPropChangeHandler(e)
{
	if (!e)
	{
		e = window.event;
	}
	var select = $(this),
		basketItemId,
		property,
		property_values = {},
		postData = {},
		action_var,
		all_sku_props,
		i,
		sku_prop_value,
		m;

	if (select.length < 1) return;

	// if already selected element is clicked
	if (select.val() == select.find('[selected]').val()) {
		return;
	}
	select.find('[selected]').removeAttr('selected');
	select.find('[value="'+select.val()+'"]').attr('selected', 'selected');

	RZB2.ajax.loader.Start($('#basket_form_container'));

	basketItemId = select.closest('tr').attr('id');
	property = select.data('property');
	action_var = BX('action_var').value;

	property_values[property] = select.val();

	// get other basket item props to get full unique set of props of the new product
	all_sku_props = $('#'+basketItemId).find('.sku select');
	all_sku_props.each(function(){
		if ($(this).attr('id') === 'prop_' + property + '_' + basketItemId) return;

		property_values[$(this).data('property')] = $(this).val();
	});

	postData = {
		'basketItemId': basketItemId,
		'sessid': BX.bitrix_sessid(),
		'site_id': BX.message('SITE_ID'),
		'props': property_values,
		'action_var': action_var,
		'select_props': BX('column_headers').value,
		'offers_props': BX('offers_props').value,
		'quantity_float': BX('quantity_float').value,
		'count_discount_4_all_quantity': BX('count_discount_4_all_quantity').value,
		'price_vat_show_value': BX('price_vat_show_value').value,
		'hide_coupon': BX('hide_coupon').value,
		'use_prepayment': BX('use_prepayment').value
	};

	postData[action_var] = 'select_item';

	BX.ajax({
		url: '/bitrix/components/bitrix/sale.basket.basket/ajax.php',
		method: 'POST',
		data: postData,
		dataType: 'json',
		onsuccess: function(result)
		{
			if (typeof result == "object" && result.CODE == 'ERROR') {
				var message = result.MESSAGE || BX.message('basket_sku_not_available');
				RZB2.ajax.showMessage(message, 'fail');
				RZB2.ajax.loader.Stop($('#basket_form_container'));
				select.closest('tr').addClass('out-of-stock').removeClass('available-for-order');
				return;
			}
			recalcBasketAjax({'BasketRefresh':'n'});
		}
	});
}

//used in template.php
function checkOut()
{
	if (!!BX('coupon'))
		BX('coupon').disabled = true;
	BX("basket_form").submit();
	return true;
}

//used in basket_items.php
function enterCoupon()
{
	var newCoupon = BX('coupon');
	if (!!newCoupon && !!newCoupon.value)
		recalcBasketAjax({'coupon' : newCoupon.value});
}

// check if quantity is valid
// and update values of both controls (text input field for PC and mobile quantity select) simultaneously
function updateQuantity(controlId, basketId, ratio, bUseFloatQuantity)
{
	var oldVal = BX(controlId).defaultValue,
		newVal = parseFloat(BX(controlId).value) || 0,
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

	var bIsQuantityFloat = false;

	if (parseInt(newVal) != parseFloat(newVal))
	{
		bIsQuantityFloat = true;
	}

	newVal = (bUseFloatQuantity === false && bIsQuantityFloat === false) ? parseInt(newVal) : parseFloat(newVal).toFixed(2);

	if (bIsCorrectQuantityForRatio)
	{
		BX(controlId).defaultValue = newVal;

		BX("QUANTITY_INPUT_" + basketId).value = newVal;

		// set hidden real quantity value (will be used in actual calculation)
		BX("QUANTITY_" + basketId).value = newVal;

		recalcBasketAjax({});
	}
	else
	{
		newVal = getCorrectRatioQuantity(newVal, ratio, bUseFloatQuantity);

		if (newVal != oldVal)
		{
			BX("QUANTITY_INPUT_" + basketId).value = newVal;
			BX("QUANTITY_" + basketId).value = newVal;
			recalcBasketAjax({});
		}else
		{
			BX(controlId).value = oldVal;
		}
	}
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

	if (ratio > 0 && newVal <= ratio) {
		newVal = ratio;
		$('#QUANTITY_DOWN_' + basketId).addClass('disabled');
	} else {
		$('#QUANTITY_DOWN_' + basketId).removeClass('disabled');
	}

	if (!bUseFloatQuantity && newVal != newVal.toFixed(2))
	{
		newVal = newVal.toFixed(2);
	}

	newVal = getCorrectRatioQuantity(newVal, ratio, bUseFloatQuantity);

	BX("QUANTITY_INPUT_" + basketId).value = newVal;
	BX("QUANTITY_INPUT_" + basketId).defaultValue = newVal;

	//updateQuantity('QUANTITY_INPUT_' + basketId, basketId, ratio, bUseFloatQuantity);
}

function getCorrectRatioQuantity(quantity, ratio, bUseFloatQuantity)
{
	var newValInt = quantity * 10000,
		ratioInt = ratio * 10000,
		reminder = newValInt % ratioInt,
		result = quantity,
		bIsQuantityFloat = false,
		i;
	ratio = parseFloat(ratio);

	if (reminder === 0)
	{
		return result;
	}

	if (ratio !== 0 && ratio != 1)
	{
		for (i = ratio, max = parseFloat(quantity) + parseFloat(ratio); i <= max; i = parseFloat(parseFloat(i) + parseFloat(ratio)).toFixed(2))
		{
			result = i;
		}

	}else if (ratio === 1)
	{
		result = quantity | 0;
	}

	if (parseInt(result, 10) != parseFloat(result))
	{
		bIsQuantityFloat = true;
	}

	result = (bUseFloatQuantity === false && bIsQuantityFloat === false) ? parseInt(result, 10) : parseFloat(result).toFixed(2);

	return result;
}
/**
 *
 * @param {} params
 */
function recalcBasketAjax(params, simpleUpdate)
{
	var property_values = {},
		action_var = BX('action_var').value,
		items = BX('basket_items'),
		delayedItems = BX('delayed_items'),
		postData,
		i;

	postData = {
		'sessid': BX.bitrix_sessid(),
		'site_id': BX.message('SITE_ID'),
		'props': property_values,
		'action_var': action_var,
		'select_props': BX('column_headers').value,
		'offers_props': BX('offers_props').value,
		'quantity_float': BX('quantity_float').value,
		'count_discount_4_all_quantity': BX('count_discount_4_all_quantity').value,
		'price_vat_show_value': BX('price_vat_show_value').value,
		'hide_coupon': BX('hide_coupon').value,
		'use_prepayment': BX('use_prepayment').value,
		'rz_ajax': 'y',
		'rz_ajax_no_header': 'y',
		'BasketRefresh': 'y',
		'self_url': basketJSParams.SELF_URL,
		'tab': $('#basket_form .um_tab.active').data('tab')
	};
	//postData[action_var] = 'recalculate';
	
	if(!simpleUpdate)
	{
		if (!!params && typeof params === 'object')
		{
			for (i in params)
			{
				if (params.hasOwnProperty(i))
					postData[i] = params[i];
			}
		}

		if (!!items && items.rows.length > 0)
		{
			for (i = 1; items.rows.length > i; i++)
			{
				if(typeof items.rows[i].id !== 'undefined' && Number(items.rows[i].id) > 0)
				{
					postData['QUANTITY_' + items.rows[i].id] = BX('QUANTITY_' + items.rows[i].id).value;
				}
			}
		}

		if (!!delayedItems && delayedItems.rows.length > 0)
		{
			for (i = 1; delayedItems.rows.length > i; i++)
			{
				if(typeof delayedItems.rows[i].id !== 'undefined' && Number(delayedItems.rows[i].id) > 0)
				{
					postData['DELAY_' + delayedItems.rows[i].id] = 'Y';
				}
			}
		}
	}
	RZB2.ajax.loader.Start($('#basket_form_container'));

	BX.ajax({
		//url: '/bitrix/components/bitrix/sale.basket.basket/ajax.php',
		url: SITE_DIR + 'ajax/big_basket.php',
		method: 'POST',
		data: postData,
		dataType: 'html',
		onsuccess: function(result)
		{
			updateBasketTable(null, result, simpleUpdate);
		}
	});
}

// BITRONIC 2 CUSTOM PART

var bigBasketTimerQuantity,
    bigBasketTimerTimeout,
    bigBasketUpdateTimeout;
$(document).ready(function(){
	$('#basket_form')
		.on('mousedown', 'button.quantity-change', function(e){
			e = e || window.event;
			var _ = $(this);
			if ( _.hasClass('disabled') ) return;

			var id = parseInt(_.closest('tr').attr('id'), 10);
			var ratio = parseFloat(_.data('ratio'));
			var sign = _.hasClass('decrease') ? 'down' : 'up';
			var bUseFloatQuantity = $('#quantity_float').val() == "Y" ? true : false;

			setQuantity(id, ratio, sign, bUseFloatQuantity);
			clearTimeout(bigBasketUpdateTimeout);

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
			var basketId = parseInt(_.closest('tr').attr('id'), 10);
			var ratio = parseFloat(_.data('ratio'));
			var bUseFloatQuantity = $('#quantity_float').val() == "Y" ? true : false;
			bigBasketUpdateTimeout = setTimeout(function () {
				updateQuantity('QUANTITY_INPUT_' + basketId, basketId, ratio, bUseFloatQuantity);
			}, 300);
		})
		.on('click', 'td.actions a, #basket-delete-all', function(e){
			var _ = $(this);
			recalcBasketAjax({
				action: _.data('action'),
				id:     _.data('id')
			});
			e.preventDefault();
			RZB2.ajax.BasketSmall.ElementsList = {};
		})
		.on('click', 'button[data-coupon]', function(e){
			var value = $(this).attr('data-coupon');
			if (!!value && value.length > 0)
			{
				recalcBasketAjax({'delete_coupon' : value});
			}
		})
		.on('click', '.coupon-link', function(e){
			$(this).remove();
			e.preventDefault();
		})
		.on('mouseenter', 'button.apply-coupon.valid', function(){
			$(this).find('i').removeClass('flaticon-check33').addClass('flaticon-x5');
		})
		.on('mouseleave', 'button.apply-coupon.valid', function(){
			$(this).find('i').removeClass('flaticon-x5').addClass('flaticon-check33');
		})
		.on('change', '.sku select', skuPropChangeHandler)
		.find('.scroll-slider-wrap').each(function(){
			initHorizontalCarousels($('#basket_form'));
			return false;
		});
});
