RZB2.ajax.CatalogSetConstructor = 
{
	AddToBasket: function(obj)
	{
		var data = {items:{}};
		data.action = 'addList';
		data.rz_ajax = 'y';
		data.iblock_id = RZB2.ajax.params['IBLOCK_ID'];
		$(obj).closest('.collection-wrap').find('.collection .item').each(function(){
			var id = $(this).data('item-id'),
				props = $(this).data('props');

			data.items[id] = {
				id: id,
				quantity: 1,
                SKUprops: props,
			};
		});
		
		this.SendRequest(data, obj);
	},
		
	SendRequest: function(data, obj)
	{
		var _ = this;
		var spinner = RZB2.ajax.spinner($(obj));
		spinner.Start();
		$.ajax({
			url: SITE_DIR + 'ajax/basket.php',
			type: "POST",
			data: data,
			dataType: 'json',
			success: function(data){
				RZB2.ajax.BasketSmall.Refresh();
				spinner.Stop();
				spinner = undefined;
                $(obj).closest('.modal').modal('hide');
			}
		});
	},
	
	Popup: function(arParams)
	{
		if ('object' === typeof arParams)
		{
			this.modalId = arParams.ID;
			this.ajaxPath = arParams.AJAX_FILE;
			this.arParams = arParams.PARAMS;
		}
		
		this.summ = 0; 
		this.summDiscount = 0; 
		this.summDiscountDiff = 0;
		
		this.InitItemsHandle();
	}
}
RZB2.ajax.CatalogSetConstructor.Popup.prototype.AddToBasket = function () {
	var data = {items:{}};
	data.action = 'addList';
	data.rz_ajax = 'y';
	data.iblock_id = RZB2.ajax.params['IBLOCK_ID'];
	var $items = $('#' + this.modalId).find('.custom-collection-content .item, .custom-collection-content .product');
	$items.each(function(){
		var id = $(this).data('item-id'),
            props = $(this).data('props');
		
		if(typeof data.items[id] !== 'undefined')
		{
			data.items[id].quantity += 1;
		}
		else
		{
			data.items[id] = {
				id: id,
				quantity: 1,
                SKUprops: props,
			};
		}
	});

	RZB2.ajax.CatalogSetConstructor.SendRequest(data, $('#'+this.modalId+' .btn-main'));
};

RZB2.ajax.CatalogSetConstructor.Popup.prototype.Load = function () {
	var $form = $('#' + this.modalId).find('.content');
	var data = {};
	data['arParams'] = this.arParams;
	this.SendRequest(data, $form);
};

RZB2.ajax.CatalogSetConstructor.Popup.prototype.SendRequest = function (data, objLoad, close) {
	if (typeof(data) == 'undefined') {
		data = {};
	}
	data["rz_ajax"] = 'Y';
	var _this = this;
	var spinner = RZB2.ajax.spinner(objLoad.parent());
	spinner.Start({color: RZB2.themeColor});
	RZB2.ajax.loader.Start(objLoad);
	$.ajax({
		type: "POST",
		url: this.ajaxPath,
		data: data,
		success: function (res) {
			_this.Refresh(res, objLoad, close, spinner);
		}
	})
};

RZB2.ajax.CatalogSetConstructor.Popup.prototype.Refresh = function (res, objLoad, close, spinner) {
	if(close)
	{
		$('#' + this.modalId).modal('hide');
	}
	if (typeof spinner == 'object') {
		spinner.Stop();
		spinner = undefined;
	}
	objLoad.empty().html(res);
	setTimeout(b2.init.collectionHandle,200);
	RZB2.utils.initLazy($(objLoad));
	RZB2.ajax.loader.Stop(objLoad);
};

RZB2.ajax.CatalogSetConstructor.Popup.prototype.InitItemsHandle = function () {
	var $modal = $('#' + this.modalId);
	
	$modal.on('click', '.final .btn-main', jQuery.proxy(this.AddToBasket, this));
	$modal.on('item.add', '.items-wrap', jQuery.proxy(this.addItem, this));
	$modal.on('item.remove', '.items-wrap', jQuery.proxy(this.deleteItem, this));
};

RZB2.ajax.CatalogSetConstructor.Popup.prototype.deleteItem = function (e, item) {
	this.summ = -parseFloat($(item).data('price'));
	this.summDiscount = -parseFloat($(item).data('discount-price')); 
	this.summDiscountDiff = -parseFloat($(item).data('discount-diff-price'));
	
	this.RecalcPrices();
};

RZB2.ajax.CatalogSetConstructor.Popup.prototype.addItem = function (e, item) {
	this.summ = parseFloat($(item).data('price'));
	this.summDiscount = parseFloat($(item).data('discount-price')); 
	this.summDiscountDiff = parseFloat($(item).data('discount-diff-price'));
	
	this.RecalcPrices();
};

RZB2.ajax.CatalogSetConstructor.Popup.prototype.RecalcPrices = function () {
	var $items = $('#' + this.modalId).find('.custom-collection-content .item');
	var currency = RZB2.ajax.BasketSmall.basketCurrency;
	var summ = this.summ, 
		summDiscount = this.summDiscount, 
		summDiscountDiff = this.summDiscountDiff;
	
	var $curElement = $('#' + this.modalId).find('.product');
	summ += parseFloat($curElement.data('price'));
	summDiscount += parseFloat($curElement.data('discount-price'));
	summDiscountDiff += parseFloat($curElement.data('discount-diff-price'));
	
	$items.each(function(){
		summ += parseFloat($(this).data('price'));
		summDiscount += parseFloat($(this).data('discount-price'));
		summDiscountDiff += parseFloat($(this).data('discount-diff-price'));
	});
	
	if(summDiscountDiff > 0)
	{
		$('#' + this.modalId).find('.price-full, .value-saved').show();
	}
	else
	{
		$('#' + this.modalId).find('.price-full, .value-saved').hide();
	}
	
	if(typeof BX.Currency == "object" && currency.length) {
		summ = BX.Currency.currencyFormat(summ, currency, false);
		summDiscount = BX.Currency.currencyFormat(summDiscount, currency, false);
		summDiscountDiff = BX.Currency.currencyFormat(summDiscountDiff, currency, false);
	} else {
		summ = from.toFixed(2);
		if (summ == Math.round(summ)) summ = Math.round(summ);
		summDiscount = from.toFixed(2);
		if (summDiscount == Math.round(summDiscount)) summDiscount = Math.round(summDiscount);
		summDiscountDiff = from.toFixed(2);
		if (summDiscountDiff == Math.round(summDiscountDiff)) summDiscountDiff = Math.round(summDiscountDiff);
	}
	
	$('#' + this.modalId).find('.price-full .value').html(summ);
	$('#' + this.modalId).find('.price-final .value').html(summDiscount);
	$('#' + this.modalId).find('.value-saved .value').html(summDiscountDiff);

}