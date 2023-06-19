(function (window) {

if (!!window.JCCatalogItem)
{
	return;
}

var BasketButton = function(params)
{
	BasketButton.superclass.constructor.apply(this, arguments);
	this.nameNode = BX.create('span', {
		props : { className : 'bx_medium bx_bt_button', id : this.id },
		style: typeof(params.style) === 'object' ? params.style : {},
		text: params.text
	});
	this.buttonNode = BX.create('span', {
		attrs: { className: params.ownerClass },
		style: { marginBottom: '0', borderBottom: '0 none transparent' },
		children: [this.nameNode],
		events : this.contextEvents
	});
	if (BX.browser.IsIE())
	{
		this.buttonNode.setAttribute("hideFocus", "hidefocus");
	}
};

if (BX.PopupWindowButton)
	BX.extend(BasketButton, BX.PopupWindowButton);

window.JCCatalogItem = function (arParams)
{
	this.skuVisualParams = {
		TEXT:
		{
			TAG_BIND: 'select',
			TAG: 'option',
			CLASS: '',
			ACTIVE_CLASS: 'active',
			HIDE_CLASS: 'bx_missing',
			EVENT: 'change',
		},
		PICT:
		{
			TAG_BIND: 'span',
			TAG: 'span',
			CLASS: 'color',
			ACTIVE_CLASS: 'active',
			HIDE_CLASS: 'bx_missing',
			EVENT: 'click',
		},
		BOX:
		{
			TAG_BIND: 'span',
			TAG: 'span',
			CLASS: 'color',
			ACTIVE_CLASS: 'active',
			HIDE_CLASS: 'bx_missing',
			EVENT: 'click',
		}
	};
	this.viewedCounter = {
		path: '/bitrix/components/bitrix/catalog.element/ajax.php',
		params: {
			AJAX: 'Y',
			SITE_ID: '',
			PRODUCT_ID: 0,
			PARENT_ID: 0
		}
	};
	this.updateViewedCount = false;
	this.currentIsSet = false;
	
	this.productType = 0;
	this.showQuantity = true;
	this.showAbsent = true;
	this.secondPict = false;
	this.showOldPrice = false;
	this.showPercent = false;
	this.showSkuProps = false;
	this.showOfferGroup = false;
	this.bigData = false;
	this.basketAction = 'ADD';
	this.showClosePopup = false;
	this.useCompare = false;
	this.useFavorite = false;
	this.hasServices = false;
	this.serviceIblockID = 0;
	this.articul = '';
	this.visual = {
		ID: '',
		PICT_ID: '',
		SECOND_PICT_ID: '',
		QUANTITY_ID: '',
		QUANTITY_UP_ID: '',
		QUANTITY_DOWN_ID: '',
		PRICE_ID: '',
		PRICE_OLD_ID: '',
		DSC_PERC: '',
		SECOND_DSC_PERC: '',
		ARTICUL_ID: '',
		DISPLAY_PROP_DIV: '',
		BASKET_PROP_DIV: '',
		OFFER_GROUP: ''
	};
	this.product = {
		checkQuantity: false,
		maxQuantity: 0,
		stepQuantity: 1,
		isDblQuantity: false,
		canBuy: true,
		canSubscription: true,
		name: '',
		pict: {},
		id: 0,
		addUrl: '',
		buyUrl: ''
	};

	this.basketMode = '';
	this.basketData = {
		useProps: false,
		emptyProps: false,
		inputProps: false,
		quantity: 'quantity',
		props: 'prop',
		basketUrl: '',
		sku_props: '',
		sku_props_var: 'basket_props',
		add_url: '',
		buy_url: ''
	};

	this.compareData = {
		compareUrl: '',
		comparePath: ''
	};
	
	this.favoriteData = {
		Url: '',
		UrlDel: '',
		Path: ''
	};

	this.defaultPict = {
		pict: null,
		secondPict: null
	};

	this.checkQuantity = false;
	this.maxQuantity = 0;
	this.minQuantity = 1;
	this.stepQuantity = 1;
	this.isDblQuantity = false;
	this.canBuy = true;
	this.currentBasisPrice = {};
	this.canSubscription = true;
	this.precision = 6;
	this.precisionFactor = Math.pow(10,this.precision);
	this.priceMatrix = false;

	this.offers = [];
	this.offerNum = 0;
	this.countSkuChangeUrl = 0;
	this.treeProps = [];
	this.obTreeRows = [];
	this.showCount = [];
	this.showStart = [];
	this.selectedValues = {};

	this.obProduct = null;
	this.obQuantity = null;
	this.obQuantityUp = null;
	this.obQuantityDown = null;
	this.obPict = null;
	this.obSecondPict = null;
	this.obPrice = null;
	this.obTree = null;
	this.obBuyBtn = null;
	this.obRequestBtn = null;
	this.obBasketActions = null;
	this.obAvailInfo = null;
	this.obAvailInfoMobile = null;
	this.obDscPerc = null;
	this.obSecondDscPerc = null;
	this.obSkuProps = null;
	this.obMeasure = null;
	this.obCompare = null;
	this.obArticul = null;

	this.obPopupWin = null;
	this.basketUrl = '';
	this.basketParams = {};

	this.treeRowShowSize = 5;
	this.treeEnableArrow = { display: '', cursor: 'pointer', opacity: 1 };
	this.treeDisableArrow = { display: '', cursor: 'default', opacity:0.2 };

	this.lastElement = false;
	this.containerHeight = 0;

	this.errorCode = 0;

	if ('object' === typeof arParams)
	{
		this.detail = !!arParams.DETAIL;
		this.quickView = !!arParams.QUICK_VIEW;
		this.accesors = !!arParams.ACCESSORS;
		if(this.detail)
		{
			this.skuSimple = !!arParams.OFFER_SIMPLE;
		}
		this.productType = parseInt(arParams.PRODUCT_TYPE, 10);
		this.showQuantity = arParams.SHOW_QUANTITY;
		this.secondPict = !!arParams.SECOND_PICT;
		this.showOldPrice = !!arParams.SHOW_OLD_PRICE;
		this.showPercent = !!arParams.SHOW_DISCOUNT_PERCENT;
		this.showSkuProps = !!arParams.SHOW_SKU_PROPS;
		this.showOfferGroup = !!arParams.OFFER_GROUP;
		this.bigData = !!arParams.BIG_DATA;
		if (!!arParams.ADD_TO_BASKET_ACTION)
		{
			this.basketAction = arParams.ADD_TO_BASKET_ACTION;
		}
		if (!!arParams.REQUEST_URI)
		{
			this.REQUEST_URI = arParams.REQUEST_URI;
		}
		if (!!arParams.SCRIPT_NAME)
		{
			this.SCRIPT_NAME = arParams.SCRIPT_NAME;
		}
		if (!!arParams.ARTICUL)
		{
			this.articul = arParams.ARTICUL;
		}
		this.showClosePopup = !!arParams.SHOW_CLOSE_POPUP;
		this.useCompare = !!arParams.DISPLAY_COMPARE;
		this.useFavorite = !!arParams.DISPLAY_FAVORITE;
		this.hasServices = !!arParams.HAS_SERVICES;
		this.serviceIblockID = parseInt(arParams.SERVICE_IBLOCK_ID, 10);

		this.visual = arParams.VISUAL;
		
		this.product['IBLOCK_ID'] = arParams.PRODUCT['IBLOCK_ID'];
		if(!!arParams.PRODUCT['IBLOCK_ID_SKU'])
		{
			this.product['IBLOCK_ID_SKU'] = arParams.PRODUCT['IBLOCK_ID_SKU'];
		}
		switch (this.productType)
		{
			case 0://no catalog
			case 1://product
			case 2://set
			case 4://??? sku offer ???
				if (!!arParams.PRODUCT && 'object' === typeof(arParams.PRODUCT))
				{
					if (this.showQuantity)
					{
						this.product.checkQuantity = arParams.PRODUCT.CHECK_QUANTITY;
						this.product.isDblQuantity = arParams.PRODUCT.QUANTITY_FLOAT;
						if (this.product.checkQuantity)
						{
							this.product.maxQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.MAX_QUANTITY) : parseInt(arParams.PRODUCT.MAX_QUANTITY, 10));
						}
						this.product.stepQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.STEP_QUANTITY) : parseInt(arParams.PRODUCT.STEP_QUANTITY, 10));

						this.checkQuantity = this.product.checkQuantity;
						this.isDblQuantity = this.product.isDblQuantity;
						this.maxQuantity = this.product.maxQuantity;
						this.stepQuantity = this.product.stepQuantity;
						if (this.isDblQuantity)
						{
							this.stepQuantity = Math.round(this.stepQuantity*this.precisionFactor)/this.precisionFactor;
						}
						if ('undefined' !== typeof(arParams.PRODUCT.MIN_QUANTITY))
						{
							this.minQuantity = this.product.minQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.MIN_QUANTITY) : parseInt(arParams.PRODUCT.MIN_QUANTITY, 10));
						}
						else
						{
							this.minQuantity = this.stepQuantity;
						}
					}
					this.product.canBuy = arParams.PRODUCT.CAN_BUY;
					this.product.canSubscription = arParams.PRODUCT.SUBSCRIPTION;
					if (!!arParams.PRODUCT.BASIS_PRICE) {
						this.currentBasisPrice = arParams.PRODUCT.BASIS_PRICE;
					}
					if (!!arParams.PRODUCT.PRICE_MATRIX) {
						this.priceMatrix = arParams.PRODUCT.PRICE_MATRIX;
					}

					this.canBuy = this.product.canBuy;
					this.canSubscription = this.product.canSubscription;

					this.product.name = arParams.PRODUCT.NAME;
					this.product.pict = arParams.PRODUCT.PICT;
					this.product.id = arParams.PRODUCT.ID;
					if (!!arParams.PRODUCT.ADD_URL)
					{
						this.product.addUrl = arParams.PRODUCT.ADD_URL;
					}
					if (!!arParams.PRODUCT.BUY_URL)
					{
						this.product.buyUrl = arParams.PRODUCT.BUY_URL;
					}
					if (!!arParams.BASKET && 'object' === typeof(arParams.BASKET))
					{
						this.basketData.useProps = !!arParams.BASKET.ADD_PROPS;
						this.basketData.emptyProps = !!arParams.BASKET.EMPTY_PROPS;
					}
				}
				else
				{
					this.errorCode = -1;
				}
				break;
				
			case 3://sku
				if (!!arParams.OFFERS && BX.type.isArray(arParams.OFFERS))
				{
					if (!!arParams.PRODUCT && 'object' === typeof(arParams.PRODUCT))
					{
						this.product.name = arParams.PRODUCT.NAME;
						this.product.id = arParams.PRODUCT.ID;
					}
					this.offers = arParams.OFFERS;
					this.offerNum = 0;
					if (!!arParams.OFFER_SELECTED)
					{
						this.offerNum = parseInt(arParams.OFFER_SELECTED, 10);
					}
					if (isNaN(this.offerNum))
					{
						this.offerNum = 0;
					}
					if (!!arParams.TREE_PROPS)
					{
						this.treeProps = arParams.TREE_PROPS;
					}
					if (!!arParams.DEFAULT_PICTURE)
					{
						this.defaultPict.pict = arParams.DEFAULT_PICTURE.PICTURE;
						this.defaultPict.secondPict = arParams.DEFAULT_PICTURE.PICTURE_SECOND;
					}
				}
				break;
			default:
				this.errorCode = -1;
		}
		if (!!arParams.BASKET && 'object' === typeof(arParams.BASKET))
		{
			if (!!arParams.BASKET.QUANTITY)
			{
				this.basketData.quantity = arParams.BASKET.QUANTITY;
			}
			if (!!arParams.BASKET.PROPS)
			{
				this.basketData.props = arParams.BASKET.PROPS;
			}
			if (!!arParams.BASKET.BASKET_URL)
			{
				this.basketData.basketUrl = arParams.BASKET.BASKET_URL;
			}
			if (3 === this.productType || 1 === this.productType)
			{
				if (!!arParams.BASKET.SKU_PROPS)
				{
					this.basketData.sku_props = arParams.BASKET.SKU_PROPS;
				}
			}
			if (!!arParams.BASKET.ADD_URL_TEMPLATE)
			{
				this.basketData.add_url = arParams.BASKET.ADD_URL_TEMPLATE;
			}
			if (!!arParams.BASKET.BUY_URL_TEMPLATE)
			{
				this.basketData.buy_url = arParams.BASKET.BUY_URL_TEMPLATE;
			}
			if (this.basketData.add_url === '' && this.basketData.buy_url === '')
			{
				this.errorCode = -1024;
			}
		}
		if (this.useCompare)
		{
			if (!!arParams.COMPARE && typeof(arParams.COMPARE) === 'object')
			{
				if (!!arParams.COMPARE.COMPARE_PATH)
				{
					this.compareData.comparePath = arParams.COMPARE.COMPARE_PATH;
				}
				if (!!arParams.COMPARE.COMPARE_URL_TEMPLATE_DEL)
				{
					this.compareData.compareUrlDel = arParams.COMPARE.COMPARE_URL_TEMPLATE_DEL;
				}
				if (!!arParams.COMPARE.COMPARE_URL_TEMPLATE)
				{
					this.compareData.compareUrl = arParams.COMPARE.COMPARE_URL_TEMPLATE;
				}
				else
				{
					this.useCompare = false;
				}
			}
			else
			{
				this.useCompare = false;
			}
		}
		
		if (this.useFavorite)
		{
			if (!!arParams.FAVORITE && typeof(arParams.FAVORITE) === 'object')
			{
				if (!!arParams.FAVORITE.FAVORITE_PATH)
				{
					this.favoriteData.Path = arParams.FAVORITE.FAVORITE_PATH;
				}
				if (!!arParams.FAVORITE.FAVORITE_URL_TEMPLATE_DEL)
				{
					this.favoriteData.UrlDel = arParams.FAVORITE.FAVORITE_URL_TEMPLATE_DEL;
				}
				if (!!arParams.FAVORITE.FAVORITE_URL_TEMPLATE)
				{
					this.favoriteData.Url = arParams.FAVORITE.FAVORITE_URL_TEMPLATE;
				}
				else
				{
					this.useFavorite = false;
				}
				if (!!arParams.VISUAL.FAVORITE_ID){
					this.contFavorite = BX(arParams.VISUAL.FAVORITE_ID);
				}
			}
			else
			{
				this.useFavorite = false;
			}
		}

		this.lastElement = (!!arParams.LAST_ELEMENT && 'Y' === arParams.LAST_ELEMENT);
	}
	if (0 === this.errorCode)
	{
		if (typeof window.frameCacheVars !== "undefined" && !isFrameDataReceived)
		{
			BX.addCustomEvent("onFrameDataReceived", BX.delegate(this.Init,this));
		}
		else
		{
			BX.ready(BX.delegate(this.Init,this));
		}
	}
};

window.JCCatalogItem.prototype.Init = function()
{
	var i = 0,
		strPrefix = '',
		TreeItems = null;

	this.obProduct = BX(this.visual.ID);
	if (!this.obProduct)
	{
		this.errorCode = -1;
	}
	this.obPictFly = BX(this.visual.PICT_FLY);
	if (!this.obPict)
	{
		// this.errorCode = -2;
	}
	if (this.secondPict && !!this.visual.SECOND_PICT_ID)
	{
		this.obSecondPict = BX(this.visual.SECOND_PICT_ID);
	}
	this.obPrice = BX(this.visual.PRICE_ID);
	this.obPriceOld = BX(this.visual.PRICE_OLD_ID);
	if (!this.obPrice)
	{
		this.errorCode = -16;
	}
	if (!!this.obPriceOld && typeof this.currentBasisPrice == "object")
	{
		if (this.currentBasisPrice.DISCOUNT_VALUE === this.currentBasisPrice.VALUE && typeof this.currentBasisPrice.VALUE != 'undefined') {
			$(this.obPriceOld).hide();
		}
	}
	if (this.showQuantity && !!this.visual.QUANTITY_ID)
	{
		this.obQuantity = BX(this.visual.QUANTITY_ID);
		if (!!this.visual.QUANTITY_UP_ID)
		{
			this.obQuantityUp = BX(this.visual.QUANTITY_UP_ID);
		}
		if (!!this.visual.QUANTITY_DOWN_ID)
		{
			this.obQuantityDown = BX(this.visual.QUANTITY_DOWN_ID);
		}
		if (!this.obQuantity) {
			this.showQuantity = false;
		}
	}
	// SKU
	if (3 === this.productType)
	{
		// SKU simple
		if(this.detail)
		{
			this.obSkuTable = BX(this.visual.SKU_TABLE);
		}
		
		// SKU extend
		if(this.offers.length > 0)
		{
			if (!!this.visual.TREE_ID)
			{
				this.obTree = BX(this.visual.TREE_ID);
				if (!this.obTree)
				{
					this.errorCode = -256;
				}
				strPrefix = this.visual.TREE_ITEM_ID;
				for (i = 0; i < this.treeProps.length; i++)
				{
					this.obTreeRows[i] = {
						// LEFT: BX(strPrefix+this.treeProps[i].ID+'_left'),
						// RIGHT: BX(strPrefix+this.treeProps[i].ID+'_right'),
						LIST: BX(strPrefix+this.treeProps[i].ID+'_list'),
						// CONT: BX(strPrefix+this.treeProps[i].ID+'_cont')
					};
					if (/*!this.obTreeRows[i].LEFT || !this.obTreeRows[i].RIGHT ||*/ !this.obTreeRows[i].LIST /*|| !this.obTreeRows[i].CONT*/)
					{
						this.errorCode = -512;
						break;
					}
				}
			}
			if (!!this.visual.QUANTITY_MEASURE)
			{
				this.obMeasure = BX(this.visual.QUANTITY_MEASURE);
			}
		}
	}

	this.obBasketActions = BX(this.visual.BASKET_ACTIONS_ID);
	if (!!this.obBasketActions)
	{
		if (!!this.visual.BUY_ID)
		{
			this.obBuyBtn = BX(this.visual.BUY_ID);
		}
		if (!!this.visual.BUY_ID_2)
		{
			this.obBuyBtn2 = BX(this.visual.BUY_ID_2);
		}
		if (!!this.visual.REQUEST_ID)
		{
			this.obRequestBtn = BX(this.visual.REQUEST_ID);
		}
	}
	if (!!this.visual.BUY_ONECLICK)
	{
		this.obBuyOneClickBtn = BX(this.visual.BUY_ONECLICK);
	}

	if (!!this.visual.COMMON_BUY_ID)
	{
		this.obCommonBuyBtn = BX(this.visual.COMMON_BUY_ID);
	}

	this.obAvailInfo = BX(this.visual.AVAILABLE_INFO);
	this.obAvailInfoMobile = BX(this.visual.AVAILABLE_INFO_MOBILE);
	this.obAvaiblExp = BX(this.visual.AVAIBILITY_EXPANDED);
	this.obArticul = BX(this.visual.ARTICUL_ID);

	if (!!this.obAvaiblExp && this.offers.length > 0){
        $(this.obAvaiblExp).data('obbx', this);
	}else if (!!this.obAvailInfo && this.offers.length > 0) {
		$(this.obAvailInfo).find('.store-info').data('obbx', this);
        $(this.obAvailInfoMobile).find('.store-info').data('obbx', this);
	}

	if (this.showPercent)
	{
		if (!!this.visual.DSC_PERC)
		{
			this.obDscPerc = BX(this.visual.DSC_PERC);
		}
		if (this.secondPict && !!this.visual.SECOND_DSC_PERC)
		{
			this.obSecondDscPerc = BX(this.visual.SECOND_DSC_PERC);
		}
	}

	if (this.showSkuProps)
	{
		if (!!this.visual.DISPLAY_PROP_DIV)
		{
			this.obSkuProps = BX(this.visual.DISPLAY_PROP_DIV);
		}
	}
	
	if (0 === this.errorCode)
	{
		var _ = this;
		if (this.showQuantity)
		{
			var timerQuantity, timerTimeout;
			var $obQuantityButtons = $();
			if (!!this.obQuantityUp)
			{
				$obQuantityButtons = $obQuantityButtons.add(this.obQuantityUp);
			}
			if (!!this.obQuantityDown)
			{
				$obQuantityButtons = $obQuantityButtons.add(this.obQuantityDown);
			}
			$obQuantityButtons.on({
				mousedown: function() {
					var $_ = $(this);
					var $quanInput = $(_.obQuantity);
					if ($quanInput.hasClass('disabled') || $quanInput.is(':disabled') || $_.hasClass('disabled') ) return;

					var changeFunc = $_.hasClass('decrease') ? BX.delegate(_.QuantityDown, _) : BX.delegate(_.QuantityUp, _);
					changeFunc();
					timerTimeout = setTimeout(function(){
						timerQuantity = setInterval(changeFunc, 100);
					}, 300);
				},
				"mouseup mouseleave": function() {
					clearTimeout(timerTimeout);
					clearInterval(timerQuantity);
				}
			});
			if (!!this.obQuantity)
			{
				BX.bind(this.obQuantity, 'change', BX.delegate(this.QuantityChange, this));
			}
		}
		switch (this.productType)
		{
			case 1://product
				break;
			case 3://sku
				// sku extend
				if (this.offers.length > 0)
				{
					for(var key in this.skuVisualParams)
					{
						var TreeItems = BX.findChildren(this.obTree, {tagName: this.skuVisualParams[key].TAG_BIND}, true);
						if (!!TreeItems && 0 < TreeItems.length)
						{
							for (i = 0; i < TreeItems.length; i++)
							{
								$(TreeItems[i]).on(this.skuVisualParams[key].EVENT, BX.delegate(this.SelectOfferProp, this));
							}
						}
					}
					this.SetCurrent();
				}
				// sku simple
				else if(this.skuSimple)
				{
					var SkuBuyBtns = BX.findChildren(this.obSkuTable, {tagName: 'button', className: 'buy'}, true);
					if (!!SkuBuyBtns && 0 < SkuBuyBtns.length)
					{
						if (this.basketAction === 'ADD')
						{
							var buyFunction = this.Add2Basket;
						}
						else
						{
							var buyFunction = this.BuyBasket;
						}
						for (i = 0; i < SkuBuyBtns.length; i++)
						{
							$(SkuBuyBtns[i]).not('.on-request').on('click', BX.delegate(buyFunction, this));
						}
					}
					
					if (this.useFavorite)
					{
						var SkuFavoriteBtns = BX.findChildren(this.obSkuTable, {tagName: 'button', className: 'favorite'}, true);
						if (!!SkuFavoriteBtns && 0 < SkuFavoriteBtns.length)
						{
							for (i = 0; i < SkuFavoriteBtns.length; i++)
							{
								$(SkuFavoriteBtns[i]).on('click', BX.delegate(this.Favorite, this));
							}
						}
					}
					
					var SkuQuanDownBtns = BX.findChildren(this.obSkuTable, {tagName: 'button', className: 'decrease'}, true);
					var SkuQuanUpBtns = BX.findChildren(this.obSkuTable, {tagName: 'button', className: 'increase'}, true);
					var SkuQuanInputs = BX.findChildren(this.obSkuTable, {tagName: 'input', className: 'quantity-input'}, true);

					$obQuantityButtons = $(SkuQuanDownBtns).add(SkuQuanUpBtns);
					$obQuantityButtons.on({
						mousedown: function() {
							var $_ = $(this);
							var $quanInput = $_.parent().find('.quantity-input');
							if ($quanInput.hasClass('disabled') || $quanInput.is(':disabled') || $_.hasClass('disabled') ) return;

							var changeFunc = $_.hasClass('decrease') ? BX.delegate(_.QuantityDownSimple, _) : BX.delegate(_.QuantityUpSimple, _);

							changeFunc.apply(this);
							timerTimeout = setTimeout(function(){
								timerQuantity = setInterval(function(){changeFunc.apply($_[0]);}, 100);
							}, 300);
						},
						"mouseup mouseleave": function() {
							clearTimeout(timerTimeout);
							clearInterval(timerQuantity);
						}
					});
					if (!!SkuQuanInputs && 0 < SkuQuanInputs.length)
					{
						for (i = 0; i < SkuQuanInputs.length; i++)
						{
							BX.bind(SkuQuanInputs[i], 'change', BX.delegate(this.QuantityChangeSimple, this));
						}
					}
				}
				
				break;
		}
		if (!!this.obBuyBtn)
		{
			if (this.basketAction === 'ADD')
			{
				BX.bind(this.obBuyBtn, 'click', BX.delegate(this.Add2Basket, this));
			}
			else
			{
				BX.bind(this.obBuyBtn, 'click', BX.delegate(this.BuyBasket, this));
			}
		}
		if (!!this.obBuyBtn2)
		{
			if (this.basketAction === 'ADD')
			{
				BX.bind(this.obBuyBtn2, 'click', BX.delegate(this.Add2Basket, this));
			}
			else
			{
				BX.bind(this.obBuyBtn2, 'click', BX.delegate(this.BuyBasket, this));
			}
		}
		if (this.lastElement)
		{
			// this.containerHeight = parseInt(this.obProduct.parentNode.offsetHeight, 10);
			// if (isNaN(this.containerHeight))
			// {
				// this.containerHeight = 0;
			// }
			// this.setHeight();
			// BX.bind(window, 'resize', BX.delegate(this.checkHeight, this));
			// BX.bind(this.obProduct.parentNode, 'mouseover', BX.delegate(this.setHeight, this));
			// BX.bind(this.obProduct.parentNode, 'mouseout', BX.delegate(this.clearHeight, this));
		}
		if (this.useCompare)
		{
			this.obCompare = BX(this.visual.COMPARE_LINK_ID);
			if (!!this.obCompare)
			{
				BX.bind(this.obCompare, 'click', BX.proxy(this.Compare, this));
			}
		}
		
		if (this.useFavorite)
		{
			this.obFavorite = BX(this.visual.FAVORITE_ID);
			if (!!this.obFavorite)
			{
				BX.bind(this.obFavorite, 'click', BX.proxy(this.Favorite, this));
			}
		}
		
		if (this.bigData)
		{
			this.obBigDataContainer = BX(this.visual.BIG_DATA_CONTAINER);			
			var detailLinks = BX.findChildren(this.obProduct, {'className':'bx_rcm_view_link'}, true);
			var _this = this;
			if (detailLinks)
			{
				for (i in detailLinks)
				{
					BX.bind(detailLinks[i], 'click', function(e){
						_this.RememberRecommendation(
							BX(this),
							BX(this).getAttribute('data-product-id')
						);
					});
				}
			}
		}
		if(this.detail && 3 === this.productType && this.offers.length > 0)
		{
			var _this = this;
			
			// this hack with $(window).load for support Safari & old Chromes
			$(document).on('click','.print-link',function(){
				PRINT = true;
			});

			$(window).load(function() {
				setTimeout(function() {
					$(window).on('popstate', function (e) {
						if (typeof PRINT == 'undefined') {
                            history.go(-_this.countSkuChangeUrl);
                        } else {
                            PRINT = undefined;
						}
						e.preventDefault(); 
					});
				}, 0);
			});
		}
		
		this.bInit = true;
		this.currentIsSet = true;
	}
};

window.JCCatalogItem.prototype.checkHeight = function()
{
	this.containerHeight = parseInt(this.obProduct.parentNode.offsetHeight, 10);
	if (isNaN(this.containerHeight))
	{
		this.containerHeight = 0;
	}
};

window.JCCatalogItem.prototype.setHeight = function()
{
	if (0 < this.containerHeight)
	{
		BX.adjust(this.obProduct.parentNode, {style: { height: this.containerHeight+'px'}});
	}
};

window.JCCatalogItem.prototype.clearHeight = function()
{
	BX.adjust(this.obProduct.parentNode, {style: { height: 'auto'}});
};

window.JCCatalogItem.prototype.blockBtnUp = function(message)
{
	message = (typeof message == "undefined") ? true : message;
	if (!!this.obQuantityUp) {
		$(this.obQuantityUp).addClass('disabled');
	}
	if (!!message) {
		RZB2.ajax.showMessage(BX.message('available-limit-msg'), 'fail');
	}
};

window.JCCatalogItem.prototype.blockBtnDown = function()
{
	if (!!this.obQuantityDown) {
		$(this.obQuantityDown).addClass('disabled');
	}
};

window.JCCatalogItem.prototype.unblockBtnUp = function()
{
	if (!!this.obQuantityUp) {
		$(this.obQuantityUp).removeClass('disabled');
	}
};

window.JCCatalogItem.prototype.unblockBtnDown = function()
{
	if (!!this.obQuantityDown) {
		$(this.obQuantityDown).removeClass('disabled');
	}
};

window.JCCatalogItem.prototype.UpdateBonus = function(count)
{
	if (!window.ITRElementBonus) return;
	if (typeof this.bonusTimeout != "undefined") {
		clearTimeout(this.bonusTimeout);
	}
	var _ = this;
	this.bonusTimeout = setTimeout(function(){
		delete _.bonusTimeout;
		bonusElemUp.UpdateBonus(count);
	}, 1000);
};

window.JCCatalogItem.prototype.QuantityUp = function()
{
	var curValue = 0,
		boolSet = true,
		calcPrice;

	if (0 === this.errorCode && this.showQuantity && this.canBuy)
	{
		curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
		if (!isNaN(curValue))
		{
			curValue += this.stepQuantity;
			if (this.checkQuantity)
			{
				if (curValue > this.maxQuantity)
				{
					boolSet = false;
					this.blockBtnUp();
				} else if (curValue == this.maxQuantity) {
					this.blockBtnUp();
				}
			}
			if (boolSet)
			{
				this.unblockBtnDown();
				if (this.isDblQuantity)
				{
					curValue = Math.round(curValue*this.precisionFactor)/this.precisionFactor;
				}
				this.obQuantity.value = curValue;
				// calcPrice = {
					// DISCOUNT_VALUE: this.currentBasisPrice.DISCOUNT_VALUE * curValue,
					// VALUE: this.currentBasisPrice.VALUE * curValue,
					// DISCOUNT_DIFF: this.currentBasisPrice.DISCOUNT_DIFF * curValue,
					// DISCOUNT_DIFF_PERCENT: this.currentBasisPrice.DISCOUNT_DIFF_PERCENT,
					// CURRENCY: this.currentBasisPrice.CURRENCY
				// };
				// this.setPrice(calcPrice);
				this.CheckQuantityPrice(curValue);
				this.UpdateCommonBuyBtn();
				this.UpdateBonus(curValue);
			}
		}
	}
};

window.JCCatalogItem.prototype.QuantityDown = function()
{
	var curValue = 0,
		boolSet = true,
		calcPrice;

	if (0 === this.errorCode && this.showQuantity && this.canBuy)
	{
		curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value): parseInt(this.obQuantity.value, 10));
		if (!isNaN(curValue))
		{
			curValue -= this.stepQuantity;
			if (curValue < this.minQuantity && curValue < this.stepQuantity)
			{
				boolSet = false;
			}
			if (curValue <= this.minQuantity && curValue <= this.stepQuantity) {
				this.blockBtnDown();
			}
			if (boolSet)
			{
				this.unblockBtnUp();
				if (this.isDblQuantity)
				{
					curValue = Math.round(curValue*this.precisionFactor)/this.precisionFactor;
				}
				this.obQuantity.value = curValue;
				// calcPrice = {
					// DISCOUNT_VALUE: this.currentBasisPrice.DISCOUNT_VALUE * curValue,
					// VALUE: this.currentBasisPrice.VALUE * curValue,
					// DISCOUNT_DIFF: this.currentBasisPrice.DISCOUNT_DIFF * curValue,
					// DISCOUNT_DIFF_PERCENT: this.currentBasisPrice.DISCOUNT_DIFF_PERCENT,
					// CURRENCY: this.currentBasisPrice.CURRENCY
				// };
				// this.setPrice(calcPrice);
				this.CheckQuantityPrice(curValue);
				this.UpdateCommonBuyBtn();
				this.UpdateBonus(curValue);
			}
		}
	}
};

window.JCCatalogItem.prototype.QuantityChange = function(e, message)
{
	var curValue = 0,
		calcPrice,
		intCount,
		count;

	if (0 === this.errorCode && this.showQuantity)
	{
		if (this.canBuy)
		{
			curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
			if (!isNaN(curValue))
			{
				if (this.checkQuantity)
				{
					if (curValue >= this.maxQuantity)
					{
						curValue = this.maxQuantity;
						this.blockBtnUp(message);
					} else {
						this.unblockBtnUp();
					}
				}
				if (curValue <= this.stepQuantity && curValue <= this.minQuantity)
				{
					curValue = (this.stepQuantity < this.minQuantity) ? this.stepQuantity : this.minQuantity;
					this.blockBtnDown();
				}
				else
				{
					count = Math.round((curValue*this.precisionFactor)/this.stepQuantity)/this.precisionFactor;
					intCount = parseInt(count, 10);
					if (isNaN(intCount))
					{
						intCount = 1;
						count = 1.1;
					}
					if (count > intCount)
					{
						curValue = (intCount <= 1 ? this.stepQuantity : intCount*this.stepQuantity);
						curValue = Math.round(curValue*this.precisionFactor)/this.precisionFactor;
					}
					this.unblockBtnDown();
				}
				this.obQuantity.value = curValue;
			}
			else
			{
				this.obQuantity.value = this.stepQuantity;
				curValue=this.stepQuantity;
				this.blockBtnDown();
			}
		}
		else
		{
			this.obQuantity.value = this.stepQuantity;
			curValue=this.stepQuantity;
			this.blockBtnDown();
		}
		// calcPrice = {
			// DISCOUNT_VALUE: this.currentBasisPrice.DISCOUNT_VALUE * this.obQuantity.value,
			// VALUE: this.currentBasisPrice.VALUE * this.obQuantity.value,
			// DISCOUNT_DIFF: this.currentBasisPrice.DISCOUNT_DIFF * this.obQuantity.value,
			// DISCOUNT_DIFF_PERCENT: this.currentBasisPrice.DISCOUNT_DIFF_PERCENT,
			// CURRENCY: this.currentBasisPrice.CURRENCY
		// };
		// this.setPrice(calcPrice);
		this.CheckQuantityPrice(this.obQuantity.value);
		this.UpdateCommonBuyBtn();
		this.UpdateBonus(this.obQuantity.value);
	}
};

window.JCCatalogItem.prototype.QuantityUpSimple = function()
{
	var curValue = 0,
		stepQuantity = 1;

	var target = $(BX.proxy_context);
	var quantityInput = target.siblings('input');
	if (0 === this.errorCode)
	{
		curValue = parseInt(quantityInput.val(), 10);
		if (!isNaN(curValue))
		{
			curValue += stepQuantity;
			if(Number(curValue) > 0) {
				quantityInput.val(curValue);
				target.siblings('.disabled').removeClass('disabled');
			}
		}
	}
};

window.JCCatalogItem.prototype.QuantityDownSimple = function()
{
	var curValue = 0,
		stepQuantity = 1;

	var target = $(BX.proxy_context);
	var quantityInput = target.siblings('input');
	if (0 === this.errorCode)
	{
		curValue = parseInt(quantityInput.val(), 10);
		if (!isNaN(curValue))
		{
			curValue -= stepQuantity;
			if(curValue > 0) {
				quantityInput.val(curValue);
			}
			if (curValue <= stepQuantity) {
				target.addClass('disabled');
			}
		}
	}
};

window.JCCatalogItem.prototype.QuantityChangeSimple = function()
{
	var curValue = 0,
		stepQuantity = 1;

	var quantityInput = $(BX.proxy_context);
	if (0 === this.errorCode)
	{
		curValue = parseInt(quantityInput.val(), 10);
		if (isNaN(curValue) || Number(curValue) <= stepQuantity)
		{
			curValue = stepQuantity;
			quantityInput.val(curValue);
			quantityInput.siblings('.decrease').addClass('disabled');
		} else {
			quantityInput.siblings('.decrease').removeClass('disabled');
		}
	}
};

window.JCCatalogItem.prototype.CheckQuantityPrice = function(quantity)
{
	var priceMatrix = this.priceMatrix;
	if (this.productType == 3 && !this.skuSimple) {
		priceMatrix = this.offers[this.offerNum].priceMatrix;
	}
	if (!priceMatrix) return;
	if (!priceMatrix.ROWS.length) return;
	
	for (var row = priceMatrix.ROWS.length - 1; row >= 0; row--) {
		var arRow = priceMatrix.ROWS[row];
		if (
			(arRow.QUANTITY_FROM <= quantity ||
			 arRow.QUANTITY_FROM == 0)
			&&
			(arRow.QUANTITY_TO >= quantity ||
			 arRow.QUANTITY_TO == 0)
		) {
			for (var key in priceMatrix.COLS) {
				var arPrice = priceMatrix.MATRIX[key][row];
				arPrice.VALUE = arPrice.PRICE;
				arPrice.HTML_VALUE = arPrice.HTML_PRICE;
				arPrice.DISCOUNT_VALUE = arPrice.DISCOUNT_PRICE;
				arPrice.HTML_DISCOUNT_VALUE = arPrice.HTML_DISCOUNT_PRICE;
				this.setPrice(arPrice);
				if (window.ITRElementBonus) {
					bonusElemUp.Update.params.MIN_PRICE = arPrice.BONUS_PRICE;
				}
				break;
			}
			break;
		}
	}
};

window.JCCatalogItem.prototype.UpdateCommonBuyBtn = function()
{
	if(!!this.obCommonBuyBtn)
	{
		var commonQuantity = 0;
		$('#catalog_section table [name="quantity"]').each(function(){
			if(Number($(this).val())>0)
			{
				commonQuantity += Number($(this).val());
			}
		});
		$(this.obCommonBuyBtn).find('.number').text(Number(commonQuantity));
		if(Number(commonQuantity) > 0)
		{
			$(this.obCommonBuyBtn).removeClass('disabled');
		}
		else
		{
			$(this.obCommonBuyBtn).addClass('disabled');
		}
	}
}
window.JCCatalogItem.prototype.QuantitySet = function(index)
{
	if (0 === this.errorCode)
	{
		this.canBuy = this.offers[index].CAN_BUY;
		this.forOrder = this.offers[index].FOR_ORDER;
		this.onRequest = this.offers[index].ON_REQUEST;

		$(this.obProduct).find(
			'.buy-block-main .price-wrap,' + // detail page
			' .prices' // section page (list)
		).toggleClass('hide', this.onRequest)
		.end().find('.buy-block-footer').toggleClass('hide', !this.canBuy);

		var $btnSubscribe = $(this.obProduct).find('a.inform-when-available');

		if (this.canBuy || this.onRequest)
		{
			if (!!this.obBuyBtn)
			{
				BX.style(this.obBuyBtn, 'display', '');
				$(this.obBasketActions).toggleClass('on-request', this.onRequest);
			}
			if (!!this.obBuyOneClickBtn)
			{
				BX.style(this.obBuyOneClickBtn, 'display', '');
                if (this.detail){
                    $(this.obBuyOneClickBtn).siblings('.text').show();
                }
			}
			if (!!this.obAvailInfo)
			{
				$(this.obAvailInfo)
				  .removeClass('out-of-stock')
				  .toggleClass('available-for-order', this.forOrder||this.onRequest)
				.find('.when-available-for-order')
				  .toggleClass('when-available-on-request', this.onRequest);

				var $text = $(this.obAvailInfo).find('.when-in-stock .info-tag .text');
				if (this.offers[index].MAX_QUANTITY > 0) {
					$text.attr('data-how-much', " ("+this.offers[index].MAX_QUANTITY+" "+this.offers[index].MEASURE+")");
				} else {
					$text.removeAttr('data-how-much');
				}
			}
			if (!!this.obAvailInfoMobile)
			{
				$(this.obAvailInfoMobile)
				  .removeClass('out-of-stock')
				  .toggleClass('available-for-order', this.forOrder||this.onRequest)
				.find('.when-available-for-order')
				  .toggleClass('when-available-on-request', this.onRequest);

				var $text = $(this.obAvailInfoMobile).find('.when-in-stock .info-tag .text');
				if (this.offers[index].MAX_QUANTITY > 0) {
					$text.attr('data-how-much', " ("+this.offers[index].MAX_QUANTITY+" "+this.offers[index].MEASURE+")");
				} else {
					$text.removeAttr('data-how-much');
				}
			}
			if (!!this.obBuyOneClickBtn)
			{
				$(this.obBuyOneClickBtn).toggleClass('hide', this.onRequest);
			}
			$btnSubscribe.hide();
		}
		else
		{
			if (!!this.obBuyBtn)
			{
				BX.style(this.obBuyBtn, 'display', 'none');
			}
			if (!!this.obBuyOneClickBtn)
			{
				BX.style(this.obBuyOneClickBtn, 'display', 'none');
				if (this.detail){
					$(this.obBuyOneClickBtn).siblings('.text').hide();
				}
			}
			if (!!this.obAvailInfo)
			{
				$(this.obAvailInfo).addClass('out-of-stock').removeClass('available-for-order');
			}
			if (!!this.obAvailInfoMobile)
			{
				$(this.obAvailInfoMobile).addClass('out-of-stock').removeClass('available-for-order');
			}
			if (!!this.obBuyOneClickBtn)
			{
				$(this.obBuyOneClickBtn).addClass('hide');
			}
			$btnSubscribe.show();
		}
		if (this.showQuantity)
		{
			$(this.obQuantity).closest(
				'.quantity-counter, ' + // section page (list)
				'.inner-quan-wrap' // detail page
				).toggleClass('hide', !this.canBuy || this.onRequest);

			this.isDblQuantity = this.offers[index].QUANTITY_FLOAT;
			this.checkQuantity = this.offers[index].CHECK_QUANTITY;
			if (this.isDblQuantity)
			{
				this.maxQuantity = parseFloat(this.offers[index].MAX_QUANTITY);
				this.stepQuantity = Math.round(parseFloat(this.offers[index].STEP_QUANTITY)*this.precisionFactor)/this.precisionFactor;
			}
			else
			{
				this.maxQuantity = parseInt(this.offers[index].MAX_QUANTITY, 10);
				this.stepQuantity = parseInt(this.offers[index].STEP_QUANTITY, 10);
			}

			this.obQuantity.value = this.stepQuantity;
			this.obQuantity.disabled = !this.canBuy;
			this.QuantityChange(null, false);
			var measure = this.offers[index].MEASURE;
			if (!!this.obMeasure)
			{
				if (!!this.offers[index].MEASURE)
				{
					BX.adjust(this.obMeasure, { html : measure});
				}
				else
				{
					BX.adjust(this.obMeasure, { html : ''});
				}
			}
			$(this.obQuantity).parent('[data-tooltip]').each(function(){
				if (typeof $(this).data('original-title') !== "undefined") {
					$(this).attr('data-original-title', measure).tooltip('fixTitle');
				} else {
					$(this).attr('title', measure);
				}
			});
		}
		this.currentBasisPrice = this.offers[index].BASIS_PRICE;
	}
};

window.JCCatalogItem.prototype.SelectOfferProp = function()
{
	var i = 0,
		value = '',
		strTreeValue = '',
		arTreeItem = [],
		RowItems = null,
		target = BX.proxy_context;

	if(typeof target.options !== 'undefined' && typeof target.options[target.selectedIndex] !== 'undefined')
		target = target.options[target.selectedIndex];
	
	if (!!target && target.hasAttribute('data-treevalue'))
	{
		strTreeValue = target.getAttribute('data-treevalue');		
		propMode = target.getAttribute('data-showmode');
		arTreeItem = strTreeValue.split('_');
		if (this.SearchOfferPropIndex(arTreeItem[0], arTreeItem[1]))
		{
			RowItems = BX.findChildren(target.parentNode, {tagName: this.skuVisualParams[propMode].TAG}, false);
			if (!!RowItems && 0 < RowItems.length)
			{
				for (i = 0; i < RowItems.length; i++)
				{
					value = RowItems[i].getAttribute('data-onevalue');
					
					// for SELECTBOXES
					if(propMode == 'TEXT')
					{
						if (value === arTreeItem[1])
						{
							RowItems[i].setAttribute('selected', 'selected');
						}
						else
						{
							RowItems[i].removeAttribute('selected');
						}
					}
					else
					{
						if (value === arTreeItem[1])
						{
							$(RowItems[i]).addClass('active');
						}
						else
						{
							$(RowItems[i]).removeClass('active');
						}
					}
				}
			}
		}
	}
};

window.JCCatalogItem.prototype.SearchOfferPropIndex = function(strPropID, strPropValue)
{
	var strName = '',
		arShowValues = false,
		i, j,
		arCanBuyValues = [],
		index = -1,
		arFilter = {},
		tmpFilter = [];

	for (i = 0; i < this.treeProps.length; i++)
	{
		if (this.treeProps[i].ID === strPropID)
		{
			index = i;
			break;
		}
	}

	if (-1 < index)
	{
		for (i = 0; i < index; i++)
		{
			strName = 'PROP_'+this.treeProps[i].ID;
			arFilter[strName] = this.selectedValues[strName];
		}
		strName = 'PROP_'+this.treeProps[index].ID;
		arShowValues = this.GetRowValues(arFilter, strName);
		if (!arShowValues)
		{
			return false;
		}
		if (!BX.util.in_array(strPropValue, arShowValues))
		{
			return false;
		}
		arFilter[strName] = strPropValue;
		for (i = index+1; i < this.treeProps.length; i++)
		{
			strName = 'PROP_'+this.treeProps[i].ID;
			arShowValues = this.GetRowValues(arFilter, strName);
			if (!arShowValues)
			{
				return false;
			}
			if (this.showAbsent)
			{
				arCanBuyValues = [];
				tmpFilter = [];
				tmpFilter = BX.clone(arFilter, true);
				for (j = 0; j < arShowValues.length; j++)
				{
					tmpFilter[strName] = arShowValues[j];
					if (this.GetCanBuy(tmpFilter))
					{
						arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
					}
				}
			}
			else
			{
				arCanBuyValues = arShowValues;
			}
			if (!!this.selectedValues[strName] && BX.util.in_array(this.selectedValues[strName], arCanBuyValues))
			{
				arFilter[strName] = this.selectedValues[strName];
			}
			else if (arCanBuyValues.length > 0)
			{
				arFilter[strName] = arCanBuyValues[0];
			} else {
				arFilter[strName] = arShowValues[0];
			}
			this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
		}
		this.selectedValues = arFilter;
		this.ChangeInfo();
	}
	return true;
};

window.JCCatalogItem.prototype.UpdateRow = function(intNumber, activeID, showID, canBuyID)
{
	var i = 0,
		value = '',
		countShow = 0,
		obData = {},
		propMode = false,
		isAvailable = false,
		isCurrent = false,
		isShown = false
		currentShowStart = 0,
		RowItems = null;

	if (-1 < intNumber && intNumber < this.obTreeRows.length) {
		propMode = this.treeProps[intNumber].SHOW_MODE;
		RowItems = BX.findChildren(this.obTreeRows[intNumber].LIST, {tagName: this.skuVisualParams[propMode].TAG}, false);

		if (!!RowItems && 0 < RowItems.length) {
			pictMode = ('PICT' === propMode || 'BOX' === propMode);
			countShow = showID.length;
			var $list = $(this.obTreeRows[intNumber].LIST);
			var isIkSelect = ($list.parent().hasClass('ik_select') && typeof $list.data('plugin_ikSelect') == "object");

			for (i = 0; i < RowItems.length; i++) {
				value = RowItems[i].getAttribute('data-onevalue');
				isAvailable = BX.util.in_array(value, canBuyID);
				isCurrent = (value === activeID);
				isShown = BX.util.in_array(value, showID);

				if (pictMode) {
					$(RowItems[i]).toggleClass('not_available', !isAvailable)
						.toggleClass(this.skuVisualParams.PICT.ACTIVE_CLASS, isCurrent)
						.toggleClass('hide', !isShown);
				} else {
					//select
					if (isIkSelect) {
						$list.ikSelect('toggleOptions', i, true, isShown);
						$list.data('plugin_ikSelect').$list.find('li.ik_select_option').eq(i).toggleClass('not_available', !isAvailable);
						if (isCurrent) {
							$list.ikSelect('select', i, true);
						}
					} else {
						$(RowItems[i]).prop('disabled', !isShown).prop('selected', false).data('available', isAvailable);
						if (isCurrent) {
							$(RowItems[i]).prop('selected', true);
						}
					}
				}
			}

            if (!this.quickView) {
                b2.init.selects && b2.init.selects($(RowItems).closest('form'));
                if (typeof b2.init.scrollbarsTargeted == "function") b2.init.scrollbarsTargeted($(RowItems).closest('form').find('.chosen-drop'));
            }

			this.showCount[intNumber] = countShow;
			this.showStart[intNumber] = currentShowStart;
		}
	}
};

window.JCCatalogItem.prototype.GetRowValues = function(arFilter, index)
{
	var i = 0,
		j,
		arValues = [],
		boolSearch = false,
		boolOneSearch = true;

	if (0 === arFilter.length)
	{
		for (i = 0; i < this.offers.length; i++)
		{
			if (!BX.util.in_array(this.offers[i].TREE[index], arValues))
			{
				arValues[arValues.length] = this.offers[i].TREE[index];
			}
		}
		boolSearch = true;
	}
	else
	{
		for (i = 0; i < this.offers.length; i++)
		{
			boolOneSearch = true;
			for (j in arFilter)
			{
				if (arFilter[j] !== this.offers[i].TREE[j])
				{
					boolOneSearch = false;
					break;
				}
			}
			if (boolOneSearch)
			{
				if (!BX.util.in_array(this.offers[i].TREE[index], arValues))
				{
					arValues[arValues.length] = this.offers[i].TREE[index];
				}
				boolSearch = true;
			}
		}
	}
	return (boolSearch ? arValues : false);
};

window.JCCatalogItem.prototype.GetCanBuy = function(arFilter)
{
	var i = 0,
		j,
		boolSearch = false,
		boolOneSearch = true;

	for (i = 0; i < this.offers.length; i++)
	{
		boolOneSearch = true;
		for (j in arFilter)
		{
			if (arFilter[j] !== this.offers[i].TREE[j])
			{
				boolOneSearch = false;
				break;
			}
		}
		if (boolOneSearch)
		{
			if (this.offers[i].CAN_BUY)
			{
				boolSearch = true;
				break;
			}
		}
	}
	return boolSearch;
};

window.JCCatalogItem.prototype.SetCurrent = function()
{
	var i = 0,
		j = 0,
		arCanBuyValues = [],
		strName = '',
		arShowValues = false,
		arFilter = {},
		tmpFilter = [],
		current = this.offers[this.offerNum].TREE;

	for (i = 0; i < this.treeProps.length; i++)
	{
		strName = 'PROP_'+this.treeProps[i].ID;
		arShowValues = this.GetRowValues(arFilter, strName);
		if (!arShowValues)
		{
			break;
		}
		if (BX.util.in_array(current[strName], arShowValues))
		{
			arFilter[strName] = current[strName];
		}
		else
		{
			arFilter[strName] = arShowValues[0];
			this.offerNum = 0;
		}
		if (this.showAbsent)
		{
			arCanBuyValues = [];
			tmpFilter = [];
			tmpFilter = BX.clone(arFilter, true);
			for (j = 0; j < arShowValues.length; j++)
			{
				tmpFilter[strName] = arShowValues[j];
				if (this.GetCanBuy(tmpFilter))
				{
					arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
				}
			}
		}
		else
		{
			arCanBuyValues = arShowValues;
		}
		this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
	}
	this.selectedValues = arFilter;
	this.ChangeInfo();
};

window.JCCatalogItem.prototype.ChangeInfo = function()
{
	var i = 0,
		j,
		index = -1,
		boolOneSearch = true;

	for (i = 0; i < this.offers.length; i++)
	{
		boolOneSearch = true;
		for (j in this.selectedValues)
		{
			if (this.selectedValues[j] !== this.offers[i].TREE[j])
			{
				boolOneSearch = false;
				break;
			}
		}
		if (boolOneSearch)
		{
			index = i;
			break;
		}
	}
	if (-1 < index)
	{
		if (this.detail && !this.quickView) {
			for (i = 0; i < this.offers.length; i++)
			{
				if (this.showOfferGroup && this.offers[i].OFFER_GROUP)
				{
					if (i !== index)
					{
						BX.adjust(BX(this.visual.OFFER_GROUP+this.offers[i].ID), { style: {display: 'none'} });
					}
				}
			}
			if (this.showOfferGroup && this.offers[index].OFFER_GROUP)
			{
				BX.adjust(BX(this.visual.OFFER_GROUP+this.offers[index].ID), { style: {display: ''} });
			}
		}

        this.obPict = BX(this.visual.PICT_ID);
        this.obPictModal = BX(this.visual.PICT_MODAL);

		if (!!this.obPict)
		{
			var obData = {
				attrs: {}
			};

			if (!!this.offers[index].PICTURE_PRINT)
			{
				obData.attrs.src = this.offers[index].PICTURE_PRINT.SRC;
				if(this.detail)
				{
					obData.attrs['data-big-src'] = this.offers[index].PICTURE_PRINT.SRC_BIG;
				}

				if (!this.detail){
                    obData.attrs['data-original'] = this.offers[index].PICTURE_PRINT.SRC;
				}
			}
			else
			{
				obData.attrs.src = this.defaultPict.pict.SRC;
				if(this.detail)
				{
					obData.attrs['data-big-src'] = this.defaultPict.pict.SRC_BIG;
				}
			}
			BX.adjust(this.obPict, obData);

			if (!!this.obPictModal)
			{
				obData.attrs.src = obData.attrs['data-big-src'];
				BX.adjust(this.obPictModal, obData);
			}
		}

        if (this.detail && b2.s.photoViewType === "zoom" && typeof b2.el.$productPhotoImg != "undefined") {
            b2.el.$productPhotoImg.each(function() {
                var $wrap = $(this);

                $wrap.magnify({
                    src: $wrap.attr('data-zoom'),
                });
            });

        }

		// catchbuy timers
		var catchbuyTimer = $('#' + this.visual.ID + '_countdown_' + this.offers[index].ID).show();
		$(this.obProduct).find('.countdown').not(catchbuyTimer).hide();
		$(this.obProduct).find('.sticker.hurry-buy').toggle(catchbuyTimer.length > 0);
		$('#photo-block').toggleClass('has-timer', (catchbuyTimer.length > 0));
		
		// photo sliders
		if(this.detail)
		{
			$('#photo-block .gallery-carousel.carousel.slide').hide();
			$('#modal_big-img .gallery-carousel.carousel.slide').hide();
		}
		else
		{
			$(this.obProduct).find('.photo-thumbs').hide();
		}
		var $slider = $('#' + this.visual.SLIDER_CONT_OF_ID+this.offers[index].ID),
		 $galleryInner = $('#' + this.visual.SLIDER_CONT_OF_INNER_ID+this.offers[index].ID),
		 $modalThumbs = $('#' + this.visual.SLIDER_MODAL_CONT_OF_ID+this.offers[index].ID),
		 $modalInner = $('#' + this.visual.SLIDER_CONT_OF_MODAL_INNER_ID+this.offers[index].ID);

        if (!!this.contFavorite){
            $(this.contFavorite).attr('data-favorite-id',this.offers[index].ID);
            RZB2.ajax.Favorite.RefreshButtons();
        }

        if(this.detail)
        {
            if ($galleryInner.length){
            	$('#photo-block .thumbnails-wrap').removeClass('active');
                $galleryInner.find('.thumbnails-wrap').addClass('active');
                $galleryInner.show();
                if (typeof b2.init.galleryCarouselUpdate != 'undefined'){
                    b2.init.galleryCarouselUpdate($galleryInner.parent());
                }
            }
            if ($modalInner.length){
                $('#modal_big-img').find('.thumbnails-frame').removeClass('active');
                $modalInner.find('.thumbnails-frame').addClass('active');
                $modalInner.show();
                if (typeof b2.init.galleryCarouselUpdate != 'undefined') {
                    b2.init.galleryCarouselUpdate($modalInner.parent());
                }
            }

            if ($modalThumbs.length){
                $modalThumbs.show();
            }
		}

		if($galleryInner.find('.thumb').length)
		{
			if(!$galleryInner.data('init')) $galleryInner.data('init', true).find('.img-wrap img').each( function(){ this.src = $(this).data('src'); $(this).removeData('src') } );
			if(this.detail)
			{
				 if($modalInner.length)
				{
					if(!$modalInner.data('init')) $modalInner.data('init', true).find('.big-img img').each( function(){ this.src = $(this).data('src'); $(this).removeData('src') } );
				}

                $('.product-photos, .modal_big-img').removeClass('no-thumbs')
			}
			else
			{
				//$slider.show();
				initPhotoThumbs($(this.obProduct));
			}

		}
		else if(this.detail)
		{
			$('.product-photos, .modal_big-img').addClass('no-thumbs');
		} else{
            $('#' + this.visual.SLIDER_CONT_OF_ID + this.offers[index].ID).show();
            initPhotoThumbs($(this.obProduct));
		}
		
		if (this.detail && !!this.obPictFly)
		{
			var obData = {
				attrs: {}
			};
			if (!!this.offers[index].PICTURE_PRINT)
			{
				obData.attrs.src = this.offers[index].PICTURE_PRINT.SRC_FLY;
			}
			else
			{
				obData.attrs.src = this.defaultPict.pict.SRC_FLY;
			}
			
			BX.adjust(this.obPictFly, obData);
		}
		
		if (this.secondPict && !!this.obSecondPict)
		{
			if (!!this.offers[index].PREVIEW_PICTURE_SECOND)
			{
				BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url('+this.offers[index].PREVIEW_PICTURE_SECOND.SRC+')'}});
			}
			else if (!!this.offers[index].PREVIEW_PICTURE.SRC)
			{
				BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url('+this.offers[index].PREVIEW_PICTURE.SRC+')'}});
			}
			else if (!!this.defaultPict.secondPict)
			{
				BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url('+this.defaultPict.secondPict.SRC+')'}});
			}
			else
			{
				BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url('+this.defaultPict.pict.SRC+')'}});
			}
		}
		if (this.showSkuProps && !!this.obSkuProps)
		{
			var $detailedTech = $('.detailed-tech');
			if (0 === this.offers[index].DISPLAY_PROPERTIES.length)
			{
				BX.adjust(this.obSkuProps, {html: ' '});
				if (
					$('.tech-info-block').length < 2 &&
					$detailedTech.find('dt').length < 1
				) {
					$detailedTech.hide();
				}
			}
			else
			{
				BX.adjust(this.obSkuProps, {html: this.offers[index].DISPLAY_PROPERTIES});
				$detailedTech.show();
			}
		}
		
		if(!!this.visual.DETAIL_LINK_CLASS && typeof this.offers[index].URL !== 'undefined' && this.offers[index].URL.length)
		{
			$(this.obProduct).find('.' + this.visual.DETAIL_LINK_CLASS).attr('href', this.offers[index].URL);
		}
		
		if (!!this.obBuyOneClickBtn)
		{
			$(this.obBuyOneClickBtn).data('id', this.offers[index].ID);
		}
		if (!!this.obBuyBtn)
		{
			$(this.obBuyBtn).attr('data-offer-id', this.offers[index].ID);
		}
		if (!!this.obRequestBtn)
		{
			$(this.obRequestBtn)
			  .attr('data-offer-id', this.offers[index].ID)
			  .attr('data-measure-name', this.offers[index].MEASURE);
		}
		if (!!this.obArticul)
		{
			var articul = this.offers[index].ARTICUL || this.articul;

			if (articul) {
				$(this.obArticul).removeClass('hidden').find('strong').text(articul);
			} else {
				$(this.obArticul).addClass('hidden');
			}
		}
		if (this.detail && !this.quickView && !!this.offers[index].URL)
		{
			RZB2.ajax.setLocation(this.offers[index].URL);
			this.countSkuChangeUrl++;
		}
		this.setPrice(this.offers[index].PRICE);

		var $btnPriceDrop = $(this.obProduct).find('a.inform-when-price-drops');
		if ($btnPriceDrop.length > 0) {
			$btnPriceDrop.data('price', this.offers[index].BUY_PRICE.DISCOUNT_VALUE);
			$btnPriceDrop.data('product', this.offers[index].ID);
			$btnPriceDrop.data('price_type', this.offers[index].BUY_PRICE.PRICE_ID);
			$btnPriceDrop.data('currency', this.offers[index].BUY_PRICE.CURRENCY);
		}
		var $btnPriceCry = $(this.obProduct).find('a.cry-for-price');
		if ($btnPriceCry.length > 0) {
			$btnPriceCry.data('price', this.offers[index].BUY_PRICE.DISCOUNT_VALUE);
			$btnPriceCry.data('product', this.offers[index].ID);
			$btnPriceCry.data('price_type', this.offers[index].BUY_PRICE.PRICE_ID);
			$btnPriceCry.data('currency', this.offers[index].BUY_PRICE.CURRENCY);
		}
		var $btnSubscribe = $(this.obProduct).find('a.inform-when-available').add( $(this.obAvailInfo).find('.when-out-of-stock .info-tag') );
		if ($btnSubscribe.length > 0) {
			$btnSubscribe.data('product', this.offers[index].ID);
		}
        var $additional = $(this.obProduct).find('.additional-price-container').length ? $(this.obProduct).find('.additional-price-container') : $(this.obProduct).closest('.modal_quick-view_content').find('.additional-price-container');
		if('ADDITIONAL_PRICES' in this.offers[index] && this.offers[index].ADDITIONAL_PRICES.length > 0) {
			$additional.removeClass('hidden');
			$additional.html(this.offers[index].ADDITIONAL_PRICES).find('.b-rub').html(BX.message('b-rub'));
			if (typeof b2.init.scrollbarsTargeted == "function") b2.init.scrollbarsTargeted($additional[0]);
		} else {
			$additional.addClass('hidden');
		}

		if (this.showPercent) {
			var $discount = $(this.obPrice).next('div.price-w-discount');
			if ($discount.length) {
				if (parseFloat(this.offers[index].PRICE.DISCOUNT_DIFF) > 0) {
					$discount.find('.text').text(this.offers[index].PRICE.DISCOUNT_DIFF_PERCENT + "%");
				} else {
					$discount.remove();
				}
			} else {
				if (parseFloat(this.offers[index].PRICE.DISCOUNT_DIFF) > 0) {
					$(this.obPrice).after('<div class="sticker price-w-discount"><span class="text">' + this.offers[index].PRICE.DISCOUNT_DIFF_PERCENT + '%</span></div>');
				}
			}
		}

		if (window.ITRElementBonus) {
			bonusElemUp.Update.params.IBLOCK_ID = this.product.IBLOCK_ID_SKU;
			bonusElemUp.Update.params.MIN_PRICE = this.offers[index].BONUS_PRICE;
			bonusElemUp.Update.params.PRODUCT_ID = this.offers[index].ID;
			//bonusElemUp.UpdateBonus(1);
		}

		this.offerNum = index;
		this.QuantitySet(this.offerNum);

		RZB2.ajax.BasketSmall.ButtonsViewStatus($(this.obBuyBtn), (typeof RZB2.ajax.BasketSmall.ElementsList[this.offers[index].ID] != "undefined"), true);
		
		if (this.detail)
		{
			this.incViewedCounter();
			if (!!this.obAvaiblExp) {
			}
		}
		this.updateStores();

        if (this.detail)
        {
        	if (this.obAvaiblExp) {
                if (this.forOrder || this.onRequest) {
                    $(this.obAvaiblExp).hide();
                    $(this.obAvaiblExp).closest('.combo-target').hide();
                    $('.combo-link[href="#' + this.obAvaiblExp.id + '"]').hide();
                } else if (this.canBuy) {
                    $(this.obAvaiblExp).show();
                    $(this.obAvaiblExp).closest('.combo-target').show();
                    if ($(this.obAvaiblExp).closest('.combo-target').length && !$(this.obAvaiblExp).closest('.combo-target').hasClass('shown') && !$(this.obAvaiblExp).closest('.combo-target').hasClass('tabs')) {
                        $(this.obAvaiblExp).hide();
                    } else if ($(this.obAvaiblExp).closest('.combo-target').hasClass('tabs') && !$(this.obAvaiblExp).closest('#product-info-sections').find('.combo-link.tab-store').hasClass('active')) {
                        $(this.obAvaiblExp).hide();
                    }

                    $('.combo-link[href="#' + this.obAvaiblExp.id + '"]').show();
                }
            }
		}
	}
};

window.JCCatalogItem.prototype.updateStores = function()
{
	BX.onCustomEvent('onCatalogStoreProductChange', [this.offers[this.offerNum].ID]);
};

window.JCCatalogItem.prototype.setPrice = function(price)
{
	var strPrice,
		obData;

	if (!!this.obPrice)
	{
		var $price = $(this.obPrice);
		var $quanPrice = $price.find('.quan-price').remove();
		$price.html(price.HTML_DISCOUNT_VALUE).append($quanPrice).find('.b-rub').html(BX.message('b-rub'));
		if (this.showOldPrice && !!this.obPriceOld)
		{
			if (price.DISCOUNT_VALUE !== price.VALUE) {
				$(this.obPriceOld).show().html(price.HTML_VALUE).find('.b-rub').html(BX.message('b-rub'));
			} else {
				$(this.obPriceOld).hide();
			}
		}
		
		if (this.showPercent)
		{
			if (price.DISCOUNT_VALUE !== price.VALUE)
			{
				obData = {
					style: {
						display: ''
					},
					html: price.DISCOUNT_DIFF_PERCENT
				};
			}
			else
			{
				obData = {
					style: {
						display: 'none'
					},
					html: ''
				};
			}
			if (!!this.obDscPerc)
			{
				BX.adjust(this.obDscPerc, obData);
			}
			if (!!this.obSecondDscPerc)
			{
				BX.adjust(this.obSecondDscPerc, obData);
			}
		}
	}
};

window.JCCatalogItem.prototype.Compare = function()
{
	var compareParams, compareLink, spinnerParams;
	
	if($(this.obCompare).hasClass('toggled'))
	{
		compareLink = this.compareData.compareUrlDel;
		this.compareData.Added = false;
	}
	else
	{
		compareLink = this.compareData.compareUrl;
		this.compareData.Added = true;
		spinnerParams = {color: RZB2.themeColor};
	}
	if (!!compareLink)
	{
		var itemId = '';
		switch (this.productType)
		{
			case 1://product
			case 2://set
			case 3://sku
			case 4://??? sku offer ???
				itemId = this.product.id;
				break;
			// case 3://sku
				// compareLink = compareLink.replace('#ID#', this.offers[this.offerNum].ID);
				// break;
		}
		
		if(this.compareData.Added)
		{
			//added
			RZB2.ajax.Compare.ElementsList[itemId] = itemId;
		}
		else
		{
			//deleted
			if(typeof RZB2.ajax.Compare.ElementsList[itemId] !== 'undefined' )
				delete RZB2.ajax.Compare.ElementsList[itemId];
		}
		
		if (typeof this.compareSpinner == 'undefined') {
			this.compareSpinner = RZB2.ajax.spinner($(this.obCompare));
			this.compareSpinner.Start(spinnerParams);
		}
		
		compareLink = compareLink.replace('#ID#', itemId.toString());
		compareParams = {
			ajax_action: 'Y'
		};
		BX.ajax.loadJSON(
			compareLink,
			compareParams,
			BX.proxy(this.CompareResult, this)
		);
	}
};

window.JCCatalogItem.prototype.CompareResult = function(result)
{
	var popupContent, popupButtons, popupTitle;
	if (!!this.obPopupWin)
	{
		this.obPopupWin.close();
	}
	if (typeof this.compareSpinner == 'object') {
		this.compareSpinner.Stop();
		this.compareSpinner = undefined;
	}
	if (typeof result !== 'object')
	{
		return false;
	}
	/*
	this.InitPopupWindow();
	popupTitle = {
		content: BX.create('div', {
			style: { marginRight: '30px', whiteSpace: 'nowrap' },
			text: BX.message('COMPARE_TITLE')
		})
	};
	*/
	
	if (result.STATUS === 'OK')
	{
		RZB2.ajax.Compare.Refresh();
		
		RZB2.ajax.Compare.ButtonsViewStatus($(this.obCompare), this.compareData.Added);
		/*
		BX.onCustomEvent('OnCompareChange');
		popupContent = '<div style="width: 96%; margin: 10px 2%; text-align: center;"><p>'+BX.message('COMPARE_MESSAGE_OK')+'</p></div>';
		if (this.showClosePopup)
		{
			popupButtons = [
				new BasketButton({
					ownerClass: this.obProduct.parentNode.parentNode.className,
					text: BX.message('BTN_MESSAGE_COMPARE_REDIRECT'),
					events: {
						click: BX.delegate(this.CompareRedirect, this)
					},
					style: {marginRight: '10px'}
				}),
				new BasketButton({
					ownerClass: this.obProduct.parentNode.parentNode.className,
					text: BX.message('BTN_MESSAGE_CLOSE_POPUP'),
					events: {
						click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
					}
				})
			];
		}
		else
		{
			popupButtons = [
				new BasketButton({
					ownerClass: this.obProduct.parentNode.parentNode.className,
					text: BX.message('BTN_MESSAGE_COMPARE_REDIRECT'),
					events: {
						click: BX.delegate(this.CompareRedirect, this)
					}
				})
			];
		}
		*/
	}
	else
	{
		RZB2.ajax.showMessage((!!result.MESSAGE ? result.MESSAGE : BX.message('BITRONIC2_COMPARE_UNKNOWN_ERROR')), 'fail');
		
		RZB2.ajax.Compare.ButtonsViewStatus($(this.obCompare), false);
		/*
		popupContent = '<div style="width: 96%; margin: 10px 2%; text-align: center;"><p>'+(!!result.MESSAGE ? result.MESSAGE : BX.message('COMPARE_UNKNOWN_ERROR'))+'</p></div>';
		popupButtons = [
			new BasketButton({
				ownerClass: this.obProduct.parentNode.parentNode.className,
				text: BX.message('BTN_MESSAGE_CLOSE'),
				events: {
					click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
				}

			})
		];
		*/
	}
	/*
	this.obPopupWin.setTitleBar(popupTitle);
	this.obPopupWin.setContent(popupContent);
	this.obPopupWin.setButtons(popupButtons);
	this.obPopupWin.show();
	*/
	return false;
};

window.JCCatalogItem.prototype.CompareRedirect = function()
{
	if (!!this.compareData.comparePath)
	{
		location.href = this.compareData.comparePath;
	}
	else
	{
		this.obPopupWin.close();
	}
};

window.JCCatalogItem.prototype.Favorite = function()
{
	var params, link, spinnerParams;
	
	if($(this.obFavorite).hasClass('toggled'))
	{
		link = this.favoriteData.UrlDel;
		this.favoriteData.Added = false;
	}
	else
	{
		link = this.favoriteData.Url;
		this.favoriteData.Added = true;
		spinnerParams = {color: RZB2.themeColor};
	}
	if (!!link)
	{
		var itemId = '';
		switch (this.productType)
		{
			case 1://product
			case 2://set
			case 4://sku offer ???
				itemId = this.product.id;
				break;
			case 3://sku
				if(this.skuSimple)
				{
					var target = $(BX.proxy_context);
					itemId = target.data('favorite-id');
				}
				else
				{
					itemId = this.offers[this.offerNum].ID;
				}
				break;
		}
		
		if(this.favoriteData.Added)
		{
			//added
			RZB2.ajax.Favorite.ElementsList[itemId] = itemId;
		}
		else
		{
			//deleted
			if(typeof RZB2.ajax.Favorite.ElementsList[itemId] !== 'undefined' )
				delete RZB2.ajax.Favorite.ElementsList[itemId];
		}
		
		if (typeof this.favoriteSpinner == 'undefined') {
			this.favoriteSpinner = RZB2.ajax.spinner($(this.obFavorite));
			this.favoriteSpinner.Start(spinnerParams);
		}
		
		link = link.replace('#ID#', itemId.toString());
		BX.ajax.loadJSON(
			link,
			params,
			BX.proxy(this.FavoriteResult, this)
		);
	}
};

window.JCCatalogItem.prototype.FavoriteResult = function(result)
{
	var popupContent, popupButtons, popupTitle;

	if (typeof this.favoriteSpinner == 'object') {
		this.favoriteSpinner.Stop();
		this.favoriteSpinner = undefined;
	}

	if (typeof result !== 'object')
	{
        if($(this.obFavorite).hasClass('toggled'))
        {
            link = this.favoriteData.UrlDel;
            this.favoriteData.Added = false;
        }
        else
        {
            link = this.favoriteData.Url;
            this.favoriteData.Added = true;
            spinnerParams = {color: RZB2.themeColor};
        }
        var itemId = this.product.id;

        if(typeof RZB2.ajax.Favorite.ElementsList[itemId] !== 'undefined' )
            delete RZB2.ajax.Favorite.ElementsList[itemId];

        alert (BX.message('error-favorite'));

		return false;
	}
	if (result.result === 'success')
	{
		RZB2.ajax.Favorite.Refresh();

		var curVal = parseInt($(this.obFavorite).find('i').text());
		if (!isNaN(curVal)) {
			curVal += this.favoriteData.Added ? 1 : -1;
			$(this.obFavorite).find('i').text(curVal);
		}
		// RZB2.ajax.Favorite.ButtonsViewStatus($(this.obFavorite), this.favoriteData.Added);
	}
	else
	{
		RZB2.ajax.showMessage((!!result.msg ? result.msg : BX.message('BITRONIC2_FAVORITE_UNKNOWN_ERROR')), 'fail');
		
		// RZB2.ajax.Favorite.ButtonsViewStatus($(this.obFavorite), false);
	}
	return false;
};

window.JCCatalogItem.prototype.InitBasketUrl = function()
{
	var itemId = 0;
	this.basketUrl = (this.basketMode === 'ADD' ? this.basketData.add_url : this.basketData.buy_url);
	switch (this.productType)
	{
		case 1://product
		case 2://set
		case 4://sku offer ???
			itemId = this.product.id.toString();

			break;
		case 3://sku
			if(this.skuSimple)
			{
				if(this.selectedOfferId)
				{
					itemId = this.selectedOfferId;
				}
			}
			else
			{
				itemId = this.offers[this.offerNum].ID;
			}
			break;
	}
	this.basketItemAdded = itemId;
	this.basketUrl = this.basketUrl.replace('#ID#', itemId);

	this.basketParams = {
		'ajax_basket': 'Y'
	};
	if (!!this.REQUEST_URI)
	{
		this.basketParams['REQUEST_URI'] = this.REQUEST_URI;
	}
	if (!!this.SCRIPT_NAME)
	{
		this.basketParams['SCRIPT_NAME'] = this.SCRIPT_NAME;
	}
	if (!!RZB2.ajax.params.CUSTOM_CACHE_KEY)
	{
		this.basketParams['CUSTOM_CACHE_KEY'] = RZB2.ajax.params.CUSTOM_CACHE_KEY;
	}
	this.basketParams['IBLOCK_ID'] = this.product['IBLOCK_ID'];
	
	if(this.skuSimple)
	{
		var target = $(BX.proxy_context);
		var quantity = target.closest('tr').find('.quantity-counter input').val();
		if(quantity)
		{
			this.basketParams[this.basketData.quantity] = quantity;
		}
	}
	else if(this.showQuantity && !!this.obQuantity)
	{
		this.basketParams[this.basketData.quantity] = this.obQuantity.value;
	}
	
	if (!!this.basketData.sku_props)
	{
		this.basketParams[this.basketData.sku_props_var] = this.basketData.sku_props;
	}
};

window.JCCatalogItem.prototype.FillBasketProps = function()
{
	if (!this.visual.BASKET_PROP_DIV)
	{
		return;
	}
	var
		i = 0,
		propCollection = null,
		foundValues = false,
		obBasketProps = null;

	if (this.basketData.useProps && !this.basketData.emptyProps)
	{
		if (!!this.obPopupWin && !!this.obPopupWin.contentContainer)
		{
			obBasketProps = this.obPopupWin.contentContainer;
		}
		else
		{
			obBasketProps = BX(this.visual.BASKET_PROP_DIV);
		}
	}
	if (!!obBasketProps)
	{
		propCollection = obBasketProps.getElementsByTagName('select');
		if (!!propCollection && !!propCollection.length)
		{
			for (i = 0; i < propCollection.length; i++)
			{
				if (!propCollection[i].disabled)
				{
					switch(propCollection[i].type.toLowerCase())
					{
						case 'select-one':
							this.basketParams[propCollection[i].name] = propCollection[i].value;
							this.inputProps = true;
							foundValues = true;
							break;
						default:
							break;
					}
				}
			}
		}
		propCollection = obBasketProps.getElementsByTagName('input');
		if (!!propCollection && !!propCollection.length)
		{
			for (i = 0; i < propCollection.length; i++)
			{
				if (!propCollection[i].disabled)
				{
					switch(propCollection[i].type.toLowerCase())
					{
						case 'hidden':
							this.basketParams[propCollection[i].name] = propCollection[i].value;
							foundValues = true;
							break;
						case 'radio':
							if (propCollection[i].checked)
							{
								this.basketParams[propCollection[i].name] = propCollection[i].value;
								this.inputProps = true;
								foundValues = true;
							}
							break;
						default:
							break;
					}
				}
			}
		}
	}
	if (!foundValues)
	{
		this.basketParams[this.basketData.props] = [];
		this.basketParams[this.basketData.props][0] = 0;
	}
};

window.JCCatalogItem.prototype.Add2Basket = function(e)
{
	this.basketMode = 'ADD';
	this.Basket(e);
};

window.JCCatalogItem.prototype.BuyBasket = function(e)
{
	this.basketMode = 'BUY';
	this.Basket(e);
};

window.JCCatalogItem.prototype.SendToBasket = function(e)
{
	if (!this.canBuy)
	{
		return;
	}
	this.InitBasketUrl();
	this.FillBasketProps();
	var event = e || window.event,
		targetId = event.target.parentElement.id,
		doSpinner = true;

	if (!this.inputProps) {
		if ( typeof RZB2.ajax.BasketSmall.ElementsList[this.basketItemAdded] != 'undefined') {
			this.BasketRedirect();
			return;
		} else {
			RZB2.ajax.BasketSmall.ElementsList[this.basketItemAdded] = this.basketItemAdded;
			RZB2.ajax.BasketSmall.ButtonsViewStatus($(this.obBuyBtn), true, true);
		}
	}

    if (this.accesors && !!this.obBuyBtn){
        doSpinner = targetId == this.obBuyBtn.id;
    }

	if (typeof this.basketSpinner == 'undefined' && !!this.obBuyBtn && doSpinner) {
		this.basketSpinner = RZB2.ajax.spinner($(this.obBuyBtn));
		this.basketSpinner.Start();
	}

    if (this.accesors && !!this.obBuyBtn2){
        doSpinner = targetId == this.obBuyBtn2.id;
    }

	if (typeof this.basketSpinner2 == 'undefined' && !!this.obBuyBtn2 && doSpinner) {
		this.basketSpinner2 = RZB2.ajax.spinner($(this.obBuyBtn2));
		this.basketSpinner2.Start();
	}
	
	// check recommendation
	if (this.bigData && this.product && this.product.id)
	{
		this.RememberRecommendation(this.obProduct, this.product.id);
	}

	BX.ajax.loadJSON(
		this.basketUrl,
		this.basketParams,
		BX.delegate(this.BasketResult, this)
	);
	if (this.hasServices) {
		var productName = this.product.name;
		if (!!this.offers && this.offers.length > 0) {
			productName = this.offers[this.offerNum].NAME;
		}
		if (this.selectedOfferId > 0 && this.offerName) {
			productName = this.offerName;
		}
		RZB2.ajax.Services.AddToBasket(productName, this.serviceIblockID, this.basketParams.quantity);
	}
};

window.JCCatalogItem.prototype.Basket = function(e)
{
	var contentBasketProps = '';
	if (!this.canBuy)
	{
		return;
	}
	switch (this.productType)
	{
	case 1://product
	case 2://set
	case 4://sku offer ???
		this.SendToBasket(e);
		break;
	case 3://sku
		if(this.skuSimple)
		{
			var target = $(BX.proxy_context);
			if(target.data('offer-id') > 0)
			{
				this.selectedOfferId = parseInt(target.data('offer-id'), 10);
				this.offerName = target.data('offer-name');
				if (typeof this.skuSpinner == 'undefined' && typeof RZB2.ajax.BasketSmall.ElementsList[this.selectedOfferId] == 'undefined') {
					this.skuSpinner = RZB2.ajax.spinner(target);
					this.skuSpinner.Start({radius: 3, width: 2});
				}
				this.SendToBasket(e);
			}
			else
			{
				this.selectedOfferId = false;
				this.ScrollToSkuTable();
			}
		}
		else
		{
			this.SendToBasket(e);
		}
		break;
	}
};

window.JCCatalogItem.prototype.BasketResult = function(arResult)
{
	var strContent = '',
		strPict = '',
		successful,
		buttons = [];

	if (!!this.obPopupWin)
	{
		this.obPopupWin.close();
	}
	if (typeof this.basketSpinner == 'object') {
		this.basketSpinner.Stop();
		this.basketSpinner = undefined;
	}
	if (typeof this.basketSpinner2 == 'object') {
		this.basketSpinner2.Stop();
		this.basketSpinner2 = undefined;
	}
	if (typeof this.skuSpinner == 'object') {
		this.skuSpinner.Stop();
		this.skuSpinner = undefined;
	}
	if ('object' !== typeof arResult)
	{
		return false;
	}
	successful = (arResult.STATUS === 'OK');
	if (successful && this.basketAction === 'BUY')
	{
		this.BasketRedirect();
	}
	else
	{
		//this.InitPopupWindow();
		if (successful)
		{
			// RZB2.ajax.showMessage((!!arResult.MESSAGE ? arResult.MESSAGE : BX.message('BITRONIC2_BASKET_SUCCESS')), 'success');
			
			var itemParams = {id: this.basketItemAdded, iblockId: this.product.IBLOCK_ID};
			if(!!this.product['IBLOCK_ID_SKU'])
			{
				itemParams.iblockIdSku = this.product['IBLOCK_ID_SKU'];
			}
			if(!!this.product['id'])
			{
				itemParams.parentId = this.product['id'];
			}
			RZB2.ajax.BasketSmall.Refresh(false, itemParams);
			/*
			BX.onCustomEvent('OnBasketChange');
			switch(this.productType)
			{
			case 1://
			case 2://
				strPict = this.product.pict.SRC;
				break;
			case 3:
				strPict = (!!this.offers[this.offerNum].PREVIEW_PICTURE ?
					this.offers[this.offerNum].PREVIEW_PICTURE.SRC :
					this.defaultPict.pict.SRC
				);
				break;
			}
			strContent = '<div style="width: 96%; margin: 10px 2%; text-align: center;"><img src="'+strPict+'" height="130"><p>'+this.product.name+'</p></div>';
			if (this.showClosePopup)
			{
				buttons = [
					new BasketButton({
						ownerClass: this.obProduct.parentNode.parentNode.className,
						text: BX.message("BTN_MESSAGE_BASKET_REDIRECT"),
						events: {
							click: BX.delegate(this.BasketRedirect, this)
						},
						style: {marginRight: '10px'}
					}),
					new BasketButton({
						ownerClass: this.obProduct.parentNode.parentNode.className,
						text: BX.message("BTN_MESSAGE_CLOSE_POPUP"),
						events: {
							click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
						}
					})
				];
			}
			else
			{
				buttons = [
					new BasketButton({
						ownerClass: this.obProduct.parentNode.parentNode.className,
						text: BX.message("BTN_MESSAGE_BASKET_REDIRECT"),
						events: {
							click: BX.delegate(this.BasketRedirect, this)
						}
					})
				];
			}
			*/
		}
		else
		{
			RZB2.ajax.showMessage((!!arResult.MESSAGE ? arResult.MESSAGE : BX.message('BITRONIC2_BASKET_UNKNOWN_ERROR')), 'fail');
			/*
			strContent = '<div style="width: 96%; margin: 10px 2%; text-align: center;"><p>'+(!!arResult.MESSAGE ? arResult.MESSAGE : BX.message('BASKET_UNKNOWN_ERROR'))+'</p></div>';
			buttons = [
				new BasketButton({
					ownerClass: this.obProduct.parentNode.parentNode.className,
					text: BX.message('BTN_MESSAGE_CLOSE'),
					events: {
						click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
					}
				})
			];
			*/
		}
		/*
		this.obPopupWin.setTitleBar({
			content: BX.create('div', {
				style: { marginRight: '30px', whiteSpace: 'nowrap' },
				text: (successful ? BX.message('TITLE_SUCCESSFUL') : BX.message('TITLE_ERROR'))
			})
		});
		this.obPopupWin.setContent(strContent);
		this.obPopupWin.setButtons(buttons);
		this.obPopupWin.show();
		*/
	}
};

window.JCCatalogItem.prototype.BasketRedirect = function()
{
	location.href = (!!this.basketData.basketUrl ? this.basketData.basketUrl : BX.message('BASKET_URL'));
};

window.JCCatalogItem.prototype.ScrollToSkuTable = function()
{
	if (!this.obSkuTable) return;

	if (this.quickView) {
		var modalOffset = $('.modal_quick-view').offset().top;
		var tableOffset = $(this.obSkuTable).offset().top;
		$('#modal_quick-view').animate({scrollTop: 30 - modalOffset + tableOffset}, 800);
	} else {
		$('html,body').animate({scrollTop: $(this.obSkuTable).offset().top-60},800);
	}
};

window.JCCatalogItem.prototype.InitPopupWindow = function()
{
	if (!!this.obPopupWin)
	{
		return;
	}
	this.obPopupWin = BX.PopupWindowManager.create('CatalogSectionBasket_'+this.visual.ID, null, {
		autoHide: false,
		offsetLeft: 0,
		offsetTop: 0,
		overlay : true,
		closeByEsc: true,
		titleBar: true,
		closeIcon: {top: '10px', right: '10px'}
	});
};

window.JCCatalogItem.prototype.getUrlVars = function()
{
	var $_GET = {}; 
	var __GET = window.location.search.substring(1).split("&"); 
	for(var i=0; i<__GET.length; i++) { 
		var getVar = __GET[i].split("="); 
		$_GET[getVar[0]] = typeof(getVar[1])=="undefined" ? "" : getVar[1]; 
	} 
	return $_GET; 
};

window.JCCatalogItem.prototype.incViewedCounter = function()
{
	if (this.currentIsSet && !this.updateViewedCount)
	{
		switch (this.productType)
		{
			case 1:
			case 2:
				this.viewedCounter.params.PRODUCT_ID = this.product.id;
				this.viewedCounter.params.PARENT_ID = this.product.id;
				break;
			case 3:
				this.viewedCounter.params.PARENT_ID = this.product.id;
				this.viewedCounter.params.PRODUCT_ID = this.skuSimple ? this.product.id : this.offers[this.offerNum].ID;
				break;
			default:
				return;
		}
		this.viewedCounter.params.SITE_ID = BX.message('SITE_ID');
		this.updateViewedCount = true;
		BX.ajax.post(
			this.viewedCounter.path,
			this.viewedCounter.params,
			BX.delegate(function(){ this.updateViewedCount = false; }, this)
		);
	}
};

window.JCCatalogItem.prototype.allowViewedCount = function(update)
{
	update = !!update;
	this.currentIsSet = true;
	if (update)
	{
		this.incViewedCounter();
	}
};

window.JCCatalogItem.prototype.RememberRecommendation = function(obj, productId)
{
	var rcmId = $('.btn-action[data-product-id="'+productId+'"] [name="bigdata_recommendation_id"]').val();
	// save to RCM_PRODUCT_LOG
	var plCookieName = '_RCM_PRODUCT_LOG';
	var plCookie = RZB2.utils.getCookie(plCookieName, BX.cookie_prefix);
	var itemFound = false;

	var cItems = [],
		cItem;

	if (plCookie)
	{
		cItems = plCookie.split('.');
	}

	var i = cItems.length;

	while (i--)
	{
		cItem = cItems[i].split('-');

		if (cItem[0] == productId)
		{
			// it's already in recommendations, update the date
			cItem = cItems[i].split('-');

			// update rcmId and date
			cItem[1] = rcmId;
			cItem[2] = BX.current_server_time;

			cItems[i] = cItem.join('-');
			itemFound = true;
		}
		else
		{
			if ((BX.current_server_time - cItem[2]) > 3600*24*30)
			{
				cItems.splice(i, 1);
			}
		}
	}

	if (!itemFound)
	{
		// add recommendation
		cItems.push([productId, rcmId, BX.current_server_time].join('-'));
	}

	// serialize
	var plNewCookie = cItems.join('.');

	RZB2.utils.setCookie(plCookieName, plNewCookie, BX.cookie_prefix);
};
})(window);