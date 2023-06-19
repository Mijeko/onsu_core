(function (window) {

	if (!!window.JCCatchBuyCatalogItem) {
		return;
	}

	var BasketButton = function (params) {
		BasketButton.superclass.constructor.apply(this, arguments);
		this.nameNode = BX.create('span', {
			props: {className: 'bx_medium bx_bt_button', id: this.id},
			style: typeof(params.style) === 'object' ? params.style : {},
			text: params.text
		});
		this.buttonNode = BX.create('span', {
			attrs: {className: params.ownerClass},
			style: {marginBottom: '0', borderBottom: '0 none transparent'},
			children: [this.nameNode],
			events: this.contextEvents
		});
		if (BX.browser.IsIE()) {
			this.buttonNode.setAttribute("hideFocus", "hidefocus");
		}
	};
	BX.extend(BasketButton, BX.PopupWindowButton);

	window.JCCatchBuyCatalogItem = function (arParams) {
		this.skuVisualParams = {
			TEXT: {
				TAG_BIND: 'select',
				TAG: 'option',
				CLASS: '',
				ACTIVE_CLASS: 'active',
				HIDE_CLASS: 'bx_missing',
				EVENT: 'change',
			},
			PICT: {
				TAG_BIND: 'span',
				TAG: 'span',
				CLASS: 'color',
				ACTIVE_CLASS: 'active',
				HIDE_CLASS: 'bx_missing',
				EVENT: 'click',
			}
		};
		this.productType = 0;
		this.showQuantity = true;
		this.showAbsent = true;
		this.secondPict = false;
		this.showOldPrice = false;
		this.showPercent = false;
		this.showSkuProps = false;
		this.basketAction = 'ADD';
		this.showClosePopup = false;
		this.useCompare = false;
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
			DISPLAY_PROP_DIV: '',
			BASKET_PROP_DIV: ''
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
		this.precisionFactor = Math.pow(10, this.precision);

		this.offers = [];
		this.offerNum = 0;
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
		this.obBasketActions = null;
		this.obAvailInfo = null;
		this.obDscPerc = null;
		this.obSecondDscPerc = null;
		this.obSkuProps = null;
		this.obMeasure = null;
		this.obCompare = null;

		this.obPopupWin = null;
		this.basketUrl = '';
		this.basketParams = {};

		this.treeRowShowSize = 5;
		this.treeEnableArrow = {display: '', cursor: 'pointer', opacity: 1};
		this.treeDisableArrow = {display: '', cursor: 'default', opacity: 0.2};

		this.lastElement = false;
		this.containerHeight = 0;

		this.errorCode = 0;

		if ('object' === typeof arParams) {
			this.detail = !!arParams.DETAIL;
			this.quickView = !!arParams.QUICK_VIEW;
			if (this.detail) {
				this.skuSimple = !!arParams.OFFER_SIMPLE;
			}
			this.productType = parseInt(arParams.PRODUCT_TYPE, 10);
			this.showQuantity = arParams.SHOW_QUANTITY;
			this.showAbsent = arParams.SHOW_ABSENT;
			this.secondPict = !!arParams.SECOND_PICT;
			this.showOldPrice = !!arParams.SHOW_OLD_PRICE;
			this.showPercent = !!arParams.SHOW_DISCOUNT_PERCENT;
			this.showSkuProps = !!arParams.SHOW_SKU_PROPS;
			if (!!arParams.ADD_TO_BASKET_ACTION) {
				this.basketAction = arParams.ADD_TO_BASKET_ACTION;
			}
			if (!!arParams.REQUEST_URI) {
				this.REQUEST_URI = arParams.REQUEST_URI;
			}
			if (!!arParams.SCRIPT_NAME) {
				this.SCRIPT_NAME = arParams.SCRIPT_NAME;
			}
			this.showClosePopup = !!arParams.SHOW_CLOSE_POPUP;
			this.useCompare = !!arParams.DISPLAY_COMPARE;

			this.visual = arParams.VISUAL;

			this.product['IBLOCK_ID'] = arParams.PRODUCT['IBLOCK_ID'];
			switch (this.productType) {
				case 1://product
				case 2://set
					if (!!arParams.PRODUCT && 'object' === typeof(arParams.PRODUCT)) {
						if (this.showQuantity) {
							this.product.checkQuantity = arParams.PRODUCT.CHECK_QUANTITY;
							this.product.isDblQuantity = arParams.PRODUCT.QUANTITY_FLOAT;
							if (this.product.checkQuantity) {
								this.product.maxQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.MAX_QUANTITY) : parseInt(arParams.PRODUCT.MAX_QUANTITY, 10));
							}
							this.product.stepQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.STEP_QUANTITY) : parseInt(arParams.PRODUCT.STEP_QUANTITY, 10));

							this.checkQuantity = this.product.checkQuantity;
							this.isDblQuantity = this.product.isDblQuantity;
							this.maxQuantity = this.product.maxQuantity;
							this.stepQuantity = this.product.stepQuantity;
							if (this.isDblQuantity) {
								this.stepQuantity = Math.round(this.stepQuantity * this.precisionFactor) / this.precisionFactor;
							}
							if ('undefined' !== typeof(arParams.PRODUCT.MIN_QUANTITY)) {
								this.minQuantity = this.product.minQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.MIN_QUANTITY) : parseInt(arParams.PRODUCT.MIN_QUANTITY, 10));
							}
							else {
								this.minQuantity = this.stepQuantity;
							}
						}
						this.product.canBuy = arParams.PRODUCT.CAN_BUY;
						this.product.canSubscription = arParams.PRODUCT.SUBSCRIPTION;
						if (!!arParams.PRODUCT.BASIS_PRICE) {
							this.currentBasisPrice = arParams.PRODUCT.BASIS_PRICE;
						}

						this.canBuy = this.product.canBuy;
						this.canSubscription = this.product.canSubscription;

						this.product.name = arParams.PRODUCT.NAME;
						this.product.pict = arParams.PRODUCT.PICT;
						this.product.id = arParams.PRODUCT.ID;
						if (!!arParams.PRODUCT.ADD_URL) {
							this.product.addUrl = arParams.PRODUCT.ADD_URL;
						}
						if (!!arParams.PRODUCT.BUY_URL) {
							this.product.buyUrl = arParams.PRODUCT.BUY_URL;
						}
						if (!!arParams.BASKET && 'object' === typeof(arParams.BASKET)) {
							this.basketData.useProps = !!arParams.BASKET.ADD_PROPS;
							this.basketData.emptyProps = !!arParams.BASKET.EMPTY_PROPS;
						}
					}
					else {
						this.errorCode = -1;
					}
					break;

				case 3://sku
					if (!!arParams.OFFERS && BX.type.isArray(arParams.OFFERS)) {
						if (!!arParams.PRODUCT && 'object' === typeof(arParams.PRODUCT)) {
							this.product.name = arParams.PRODUCT.NAME;
							this.product.id = arParams.PRODUCT.ID;
						}
						this.offers = arParams.OFFERS;
						this.offerNum = 0;
						if (!!arParams.OFFER_SELECTED) {
							this.offerNum = parseInt(arParams.OFFER_SELECTED, 10);
						}
						if (isNaN(this.offerNum)) {
							this.offerNum = 0;
						}
						if (!!arParams.TREE_PROPS) {
							this.treeProps = arParams.TREE_PROPS;
						}
						if (!!arParams.DEFAULT_PICTURE) {
							this.defaultPict.pict = arParams.DEFAULT_PICTURE.PICTURE;
							this.defaultPict.secondPict = arParams.DEFAULT_PICTURE.PICTURE_SECOND;
						}
					}
					break;
				default:
					this.errorCode = -1;
			}
			if (!!arParams.BASKET && 'object' === typeof(arParams.BASKET)) {
				if (!!arParams.BASKET.QUANTITY) {
					this.basketData.quantity = arParams.BASKET.QUANTITY;
				}
				if (!!arParams.BASKET.PROPS) {
					this.basketData.props = arParams.BASKET.PROPS;
				}
				if (!!arParams.BASKET.BASKET_URL) {
					this.basketData.basketUrl = arParams.BASKET.BASKET_URL;
				}
				if (3 === this.productType) {
					if (!!arParams.BASKET.SKU_PROPS) {
						this.basketData.sku_props = arParams.BASKET.SKU_PROPS;
					}
				}
				if (!!arParams.BASKET.ADD_URL_TEMPLATE) {
					this.basketData.add_url = arParams.BASKET.ADD_URL_TEMPLATE;
				}
				if (!!arParams.BASKET.BUY_URL_TEMPLATE) {
					this.basketData.buy_url = arParams.BASKET.BUY_URL_TEMPLATE;
				}
				if (this.basketData.add_url === '' && this.basketData.buy_url === '') {
					this.errorCode = -1024;
				}
			}
			if (this.useCompare) {
				if (!!arParams.COMPARE && typeof(arParams.COMPARE) === 'object') {
					if (!!arParams.COMPARE.COMPARE_PATH) {
						this.compareData.comparePath = arParams.COMPARE.COMPARE_PATH;
					}
					if (!!arParams.COMPARE.COMPARE_URL_TEMPLATE_DEL) {
						this.compareData.compareUrlDel = arParams.COMPARE.COMPARE_URL_TEMPLATE_DEL;
					}
					if (!!arParams.COMPARE.COMPARE_URL_TEMPLATE) {
						this.compareData.compareUrl = arParams.COMPARE.COMPARE_URL_TEMPLATE;
					}
					else {
						this.useCompare = false;
					}
				}
				else {
					this.useCompare = false;
				}
			}

			this.lastElement = (!!arParams.LAST_ELEMENT && 'Y' === arParams.LAST_ELEMENT);
		}
		if (0 === this.errorCode) {
			BX.ready(BX.delegate(this.Init, this));
		}
	};

	window.JCCatchBuyCatalogItem.prototype.Init = function () {
		var i = 0,
			strPrefix = '',
			TreeItems = null;

		this.obProduct = BX(this.visual.ID);
		if (!this.obProduct) {
			this.errorCode = -1;
		}
		this.obPict = BX(this.visual.PICT_ID);
		this.obPictModal = BX(this.visual.PICT_MODAL);
		this.obPictFly = BX(this.visual.PICT_FLY);
		if (!this.obPict) {
			// this.errorCode = -2;
		}
		if (this.secondPict && !!this.visual.SECOND_PICT_ID) {
			this.obSecondPict = BX(this.visual.SECOND_PICT_ID);
		}
		this.obPrice = BX(this.visual.PRICE_ID);
		this.obPriceOld = BX(this.visual.PRICE_OLD_ID);
		if (!this.obPrice) {
			this.errorCode = -16;
		}
		if (this.showQuantity && !!this.visual.QUANTITY_ID) {
			this.obQuantity = BX(this.visual.QUANTITY_ID);
			if (!!this.visual.QUANTITY_UP_ID) {
				this.obQuantityUp = BX(this.visual.QUANTITY_UP_ID);
			}
			if (!!this.visual.QUANTITY_DOWN_ID) {
				this.obQuantityDown = BX(this.visual.QUANTITY_DOWN_ID);
			}
		}
		// SKU
		if (3 === this.productType) {
			// SKU simple
			if (this.detail) {
				this.obSkuTable = BX(this.visual.SKU_TABLE);
			}

			// SKU extend
			if (this.offers.length > 0) {
				if (!!this.visual.TREE_ID) {
					this.obTree = BX(this.visual.TREE_ID);
					if (!this.obTree) {
						this.errorCode = -256;
					}
					strPrefix = this.visual.TREE_ITEM_ID;
					for (i = 0; i < this.treeProps.length; i++) {
						this.obTreeRows[i] = {
							// LEFT: BX(strPrefix+this.treeProps[i].ID+'_left'),
							// RIGHT: BX(strPrefix+this.treeProps[i].ID+'_right'),
							LIST: BX(strPrefix + this.treeProps[i].ID + '_list'),
							// CONT: BX(strPrefix+this.treeProps[i].ID+'_cont')
						};
						if (/*!this.obTreeRows[i].LEFT || !this.obTreeRows[i].RIGHT ||*/ !this.obTreeRows[i].LIST /*|| !this.obTreeRows[i].CONT*/) {
							this.errorCode = -512;
							break;
						}
					}
				}
				if (!!this.visual.QUANTITY_MEASURE) {
					this.obMeasure = BX(this.visual.QUANTITY_MEASURE);
				}
			}
		}

		this.obBasketActions = BX(this.visual.BASKET_ACTIONS_ID);
		if (!!this.obBasketActions) {
			if (!!this.visual.BUY_ID) {
				this.obBuyBtn = BX(this.visual.BUY_ID);
			}
		}
		if (!!this.visual.BUY_ONECLICK) {
			this.obBuyOneClickBtn = BX(this.visual.BUY_ONECLICK);
		}

		if (!!this.visual.COMMON_BUY_ID) {
			this.obCommonBuyBtn = BX(this.visual.COMMON_BUY_ID);
		}

		this.obAvailInfo = BX(this.visual.AVAILABLE_INFO);

		if (this.showPercent) {
			if (!!this.visual.DSC_PERC) {
				this.obDscPerc = BX(this.visual.DSC_PERC);
			}
			if (this.secondPict && !!this.visual.SECOND_DSC_PERC) {
				this.obSecondDscPerc = BX(this.visual.SECOND_DSC_PERC);
			}
		}

		if (this.showSkuProps) {
			if (!!this.visual.DISPLAY_PROP_DIV) {
				this.obSkuProps = BX(this.visual.DISPLAY_PROP_DIV);
			}
		}

		if (0 === this.errorCode) {
			if (this.showQuantity) {
				if (!!this.obQuantityUp) {
					BX.bind(this.obQuantityUp, 'click', BX.delegate(this.QuantityUp, this));
				}
				if (!!this.obQuantityDown) {
					BX.bind(this.obQuantityDown, 'click', BX.delegate(this.QuantityDown, this));
				}
				if (!!this.obQuantity) {
					BX.bind(this.obQuantity, 'change', BX.delegate(this.QuantityChange, this));
				}
			}
			switch (this.productType) {
				case 1://product
					break;
				case 3://sku
					// sku extend
					if (this.offers.length > 0) {
						for (var key in this.skuVisualParams) {
							var TreeItems = BX.findChildren(this.obTree, {tagName: this.skuVisualParams[key].TAG_BIND}, true);
							if (!!TreeItems && 0 < TreeItems.length) {
								for (i = 0; i < TreeItems.length; i++) {
									// BX.bind(TreeItems[i], this.skuVisualParams[key].EVENT, BX.delegate(this.SelectOfferProp, this));
									$(TreeItems[i]).on(this.skuVisualParams[key].EVENT, BX.delegate(this.SelectOfferProp, this));
								}
							}
						}
						this.SetCurrent();
					}
					// sku simple
					else if (this.skuSimple) {
						var SkuBuyBtns = BX.findChildren(this.obSkuTable, {tagName: 'button', className: 'buy'}, true);
						if (!!SkuBuyBtns && 0 < SkuBuyBtns.length) {
							if (this.basketAction === 'ADD') {
								var buyFunction = this.Add2Basket;
							}
							else {
								var buyFunction = this.BuyBasket;
							}
							for (i = 0; i < SkuBuyBtns.length; i++) {
								$(SkuBuyBtns[i]).on('click', BX.delegate(buyFunction, this));
							}
						}

						if (this.showQuantity) {
							var SkuQuanDownBtns = BX.findChildren(this.obSkuTable, {tagName: 'button', className: 'decrease'}, true);
							var SkuQuanUpBtns = BX.findChildren(this.obSkuTable, {tagName: 'button', className: 'increase'}, true);
							var SkuQuanInputs = BX.findChildren(this.obSkuTable, {tagName: 'input', className: 'quantity-input'}, true);
							if (!!SkuQuanDownBtns && 0 < SkuQuanDownBtns.length) {
								for (i = 0; i < SkuQuanDownBtns.length; i++) {
									BX.bind(SkuQuanDownBtns[i], 'click', BX.delegate(this.QuantityDownSimple, this));
								}
							}
							if (!!SkuQuanUpBtns && 0 < SkuQuanUpBtns.length) {
								for (i = 0; i < SkuQuanUpBtns.length; i++) {
									BX.bind(SkuQuanUpBtns[i], 'click', BX.delegate(this.QuantityUpSimple, this));
								}
							}
							if (!!SkuQuanInputs && 0 < SkuQuanInputs.length) {
								for (i = 0; i < SkuQuanInputs.length; i++) {
									BX.bind(SkuQuanInputs[i], 'change', BX.delegate(this.QuantityChangeSimple, this));
								}
							}
						}
					}

					break;
			}
			if (!!this.obBuyBtn) {
				if (this.basketAction === 'ADD') {
					BX.bind(this.obBuyBtn, 'click', BX.delegate(this.Add2Basket, this));
				}
				else {
					BX.bind(this.obBuyBtn, 'click', BX.delegate(this.BuyBasket, this));
				}
			}
			if (this.lastElement) {
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
			if (this.useCompare) {
				this.obCompare = BX(this.visual.COMPARE_LINK_ID);
				if (!!this.obCompare) {
					BX.bind(this.obCompare, 'click', BX.proxy(this.Compare, this));
				}
			}
		}
	};

	window.JCCatchBuyCatalogItem.prototype.checkHeight = function () {
		this.containerHeight = parseInt(this.obProduct.parentNode.offsetHeight, 10);
		if (isNaN(this.containerHeight)) {
			this.containerHeight = 0;
		}
	};

	window.JCCatchBuyCatalogItem.prototype.setHeight = function () {
		if (0 < this.containerHeight) {
			BX.adjust(this.obProduct.parentNode, {style: {height: this.containerHeight + 'px'}});
		}
	};

	window.JCCatchBuyCatalogItem.prototype.clearHeight = function () {
		BX.adjust(this.obProduct.parentNode, {style: {height: 'auto'}});
	};

	window.JCCatchBuyCatalogItem.prototype.QuantityUp = function () {
		var curValue = 0,
			boolSet = true,
			calcPrice;

		if (0 === this.errorCode && this.showQuantity && this.canBuy) {
			curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
			if (!isNaN(curValue)) {
				curValue += this.stepQuantity;
				if (this.checkQuantity) {
					if (curValue > this.maxQuantity) {
						boolSet = false;
					}
				}
				if (boolSet) {
					if (this.isDblQuantity) {
						curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
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
					this.UpdateCommonBuyBtn();
				}
			}
		}
	};

	window.JCCatchBuyCatalogItem.prototype.QuantityDown = function () {
		var curValue = 0,
			boolSet = true,
			calcPrice;

		if (0 === this.errorCode && this.showQuantity && this.canBuy) {
			curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
			if (!isNaN(curValue)) {
				curValue -= this.stepQuantity;
				if (curValue < this.minQuantity) {
					boolSet = false;
				}
				if (boolSet) {
					if (this.isDblQuantity) {
						curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
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
					this.UpdateCommonBuyBtn();
				}
			}
		}
	};

	window.JCCatchBuyCatalogItem.prototype.QuantityChange = function () {
		var curValue = 0,
			calcPrice,
			intCount,
			count;

		if (0 === this.errorCode && this.showQuantity) {
			if (this.canBuy) {
				curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
				if (!isNaN(curValue)) {
					if (this.checkQuantity) {
						if (curValue > this.maxQuantity) {
							curValue = this.maxQuantity;
						}
					}
					if (curValue < this.stepQuantity) {
						curValue = this.stepQuantity;
					}
					else {
						count = Math.round((curValue * this.precisionFactor) / this.stepQuantity) / this.precisionFactor;
						intCount = parseInt(count, 10);
						if (isNaN(intCount)) {
							intCount = 1;
							count = 1.1;
						}
						if (count > intCount) {
							curValue = (intCount <= 1 ? this.stepQuantity : intCount * this.stepQuantity);
							curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
						}
					}
					this.obQuantity.value = curValue;
				}
				else {
					this.obQuantity.value = this.stepQuantity;
				}
			}
			else {
				this.obQuantity.value = this.stepQuantity;
			}
			// calcPrice = {
			// DISCOUNT_VALUE: this.currentBasisPrice.DISCOUNT_VALUE * this.obQuantity.value,
			// VALUE: this.currentBasisPrice.VALUE * this.obQuantity.value,
			// DISCOUNT_DIFF: this.currentBasisPrice.DISCOUNT_DIFF * this.obQuantity.value,
			// DISCOUNT_DIFF_PERCENT: this.currentBasisPrice.DISCOUNT_DIFF_PERCENT,
			// CURRENCY: this.currentBasisPrice.CURRENCY
			// };
			// this.setPrice(calcPrice);
			this.UpdateCommonBuyBtn();
		}
	};

	window.JCCatchBuyCatalogItem.prototype.QuantityUpSimple = function () {
		var curValue = 0,
			stepQuantity = 1;

		var target = $(BX.proxy_context);
		var quantityInput = target.siblings('input');
		if (0 === this.errorCode && this.showQuantity) {
			curValue = parseInt(quantityInput.val(), 10);
			if (!isNaN(curValue)) {
				curValue += stepQuantity;
				if (Number(curValue) > 0)
					quantityInput.val(curValue);
			}
		}
	};

	window.JCCatchBuyCatalogItem.prototype.QuantityDownSimple = function () {
		var curValue = 0,
			stepQuantity = 1;

		var target = $(BX.proxy_context);
		var quantityInput = target.siblings('input');
		if (0 === this.errorCode && this.showQuantity) {
			curValue = parseInt(quantityInput.val(), 10);
			if (!isNaN(curValue)) {
				curValue -= stepQuantity;
				if (Number(curValue) > 0)
					quantityInput.val(curValue);
			}
		}
	};

	window.JCCatchBuyCatalogItem.prototype.QuantityChangeSimple = function () {
		var curValue = 0,
			stepQuantity = 1;

		var quantityInput = $(BX.proxy_context);
		if (0 === this.errorCode && this.showQuantity) {
			curValue = parseInt(quantityInput.val(), 10);
			if (isNaN(curValue) || Number(curValue) <= 0) {
				curValue = stepQuantity;
				quantityInput.val(curValue);
			}
		}
	};

	window.JCCatchBuyCatalogItem.prototype.UpdateCommonBuyBtn = function () {
		if (!!this.obCommonBuyBtn) {
			var commonQuantity = 0;
			$('#catalog_section table [name="quantity"]').each(function () {
				if (Number($(this).val()) > 0) {
					commonQuantity += Number($(this).val());
				}
			});
			$(this.obCommonBuyBtn).find('.number').text(Number(commonQuantity));
			if (Number(commonQuantity) > 0) {
				$(this.obCommonBuyBtn).removeClass('disabled');
			}
			else {
				$(this.obCommonBuyBtn).addClass('disabled');
			}
		}
	}
	window.JCCatchBuyCatalogItem.prototype.QuantitySet = function (index) {
		if (0 === this.errorCode) {
			this.canBuy = this.offers[index].CAN_BUY;
			if (this.canBuy) {
				if (!!this.obBasketActions) {
					BX.style(this.obBasketActions, 'display', '');
				}
				if (!!this.obAvailInfo) {
					$(this.obAvailInfo).removeClass('out-of-stock');
				}
			}
			else {
				if (!!this.obBasketActions) {
					BX.style(this.obBasketActions, 'display', 'none');
				}
				if (!!this.obAvailInfo) {
					$(this.obAvailInfo).addClass('out-of-stock');
				}
			}
			if (this.showQuantity) {
				this.isDblQuantity = this.offers[index].QUANTITY_FLOAT;
				this.checkQuantity = this.offers[index].CHECK_QUANTITY;
				if (this.isDblQuantity) {
					this.maxQuantity = parseFloat(this.offers[index].MAX_QUANTITY);
					this.stepQuantity = Math.round(parseFloat(this.offers[index].STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor;
				}
				else {
					this.maxQuantity = parseInt(this.offers[index].MAX_QUANTITY, 10);
					this.stepQuantity = parseInt(this.offers[index].STEP_QUANTITY, 10);
				}

				this.obQuantity.value = this.stepQuantity;
				this.obQuantity.disabled = !this.canBuy;
				if (!!this.obMeasure) {
					if (!!this.offers[index].MEASURE) {
						BX.adjust(this.obMeasure, {html: this.offers[index].MEASURE});
					}
					else {
						BX.adjust(this.obMeasure, {html: ''});
					}
				}
			}
			this.currentBasisPrice = this.offers[index].BASIS_PRICE;
		}
	};

	window.JCCatchBuyCatalogItem.prototype.SelectOfferProp = function () {
		var i = 0,
			value = '',
			strTreeValue = '',
			arTreeItem = [],
			RowItems = null,
			target = BX.proxy_context;

		if (typeof target.options !== 'undefined' && typeof target.options[target.selectedIndex] !== 'undefined')
			target = target.options[target.selectedIndex];

		if (!!target && target.hasAttribute('data-treevalue')) {
			strTreeValue = target.getAttribute('data-treevalue');
			propMode = target.getAttribute('data-showmode');
			arTreeItem = strTreeValue.split('_');
			if (this.SearchOfferPropIndex(arTreeItem[0], arTreeItem[1])) {
				RowItems = BX.findChildren(target.parentNode, {tagName: this.skuVisualParams[propMode].TAG}, false);
				if (!!RowItems && 0 < RowItems.length) {
					for (i = 0; i < RowItems.length; i++) {
						value = RowItems[i].getAttribute('data-onevalue');

						// for SELECTBOXES
						if (propMode == 'TEXT') {
							if (value === arTreeItem[1]) {
								RowItems[i].setAttribute('selected', 'selected');
							}
							else {
								RowItems[i].removeAttribute('selected');
							}
						}
						else {
							if (value === arTreeItem[1]) {
								$(RowItems[i]).addClass('active');
							}
							else {
								$(RowItems[i]).removeClass('active');
							}
						}
					}
				}
			}
		}
	};

	window.JCCatchBuyCatalogItem.prototype.SearchOfferPropIndex = function (strPropID, strPropValue) {
		var strName = '',
			arShowValues = false,
			i, j,
			arCanBuyValues = [],
			index = -1,
			arFilter = {},
			tmpFilter = [];

		for (i = 0; i < this.treeProps.length; i++) {
			if (this.treeProps[i].ID === strPropID) {
				index = i;
				break;
			}
		}

		if (-1 < index) {
			for (i = 0; i < index; i++) {
				strName = 'PROP_' + this.treeProps[i].ID;
				arFilter[strName] = this.selectedValues[strName];
			}
			strName = 'PROP_' + this.treeProps[index].ID;
			arShowValues = this.GetRowValues(arFilter, strName);
			if (!arShowValues) {
				return false;
			}
			if (!BX.util.in_array(strPropValue, arShowValues)) {
				return false;
			}
			arFilter[strName] = strPropValue;
			for (i = index + 1; i < this.treeProps.length; i++) {
				strName = 'PROP_' + this.treeProps[i].ID;
				arShowValues = this.GetRowValues(arFilter, strName);
				if (!arShowValues) {
					return false;
				}
				if (this.showAbsent) {
					arCanBuyValues = [];
					tmpFilter = [];
					tmpFilter = BX.clone(arFilter, true);
					for (j = 0; j < arShowValues.length; j++) {
						tmpFilter[strName] = arShowValues[j];
						if (this.GetCanBuy(tmpFilter)) {
							arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
						}
					}
				}
				else {
					arCanBuyValues = arShowValues;
				}
				if (!!this.selectedValues[strName] && BX.util.in_array(this.selectedValues[strName], arCanBuyValues)) {
					arFilter[strName] = this.selectedValues[strName];
				}
				else {
					arFilter[strName] = arCanBuyValues[0];
				}
				this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
			}
			this.selectedValues = arFilter;
			this.ChangeInfo();
		}
		return true;
	};

	window.JCCatchBuyCatalogItem.prototype.UpdateRow = function (intNumber, activeID, showID, canBuyID) {
		var i = 0,
			showI = 0,
			value = '',
			countShow = 0,
			strNewLen = '',
			obData = {},
			propMode = false,
			extShowMode = false,
			isCurrent = false,
			selectIndex = 0,
			obLeft = this.treeEnableArrow,
			obRight = this.treeEnableArrow,
			currentShowStart = 0,
			RowItems = null;

		if (-1 < intNumber && intNumber < this.obTreeRows.length) {
			propMode = this.treeProps[intNumber].SHOW_MODE;
			RowItems = BX.findChildren(this.obTreeRows[intNumber].LIST, {tagName: this.skuVisualParams[propMode].TAG}, false);
			if (!!RowItems && 0 < RowItems.length) {
				pictMode = ('PICT' === propMode);
				countShow = showID.length;
				obData = {
					style: {},
					props: {
						disabled: '',
						selected: '',
					},
				};
				for (i = 0; i < RowItems.length; i++) {
					value = RowItems[i].getAttribute('data-onevalue');
					isCurrent = (value === activeID);
					if (pictMode) {
						obData.props.className = this.skuVisualParams.PICT.CLASS + ' ' + (isCurrent ? this.skuVisualParams.PICT.ACTIVE_CLASS : '');
						obData.style.display = 'none';
					}
					else {
						obData.props.selected = (isCurrent ? 'selected' : '');
						obData.props.disabled = 'disabled';
					}

					if (BX.util.in_array(value, showID)) {
						if (pictMode) {
							obData.style.display = '';
						}
						else {
							obData.props.disabled = '';
						}
						if (isCurrent) {
							selectIndex = showI;
						}
						showI++;
					}
					BX.adjust(RowItems[i], obData);
				}
				if (pictMode) {

				}
				else {
					if ($(this.obTreeRows[intNumber].LIST).parent().hasClass('ik_select'))
						$(this.obTreeRows[intNumber].LIST).ikSelect('reset');
				}
				this.showCount[intNumber] = countShow;
				this.showStart[intNumber] = currentShowStart;
			}
		}
	};

	window.JCCatchBuyCatalogItem.prototype.GetRowValues = function (arFilter, index) {
		var i = 0,
			j,
			arValues = [],
			boolSearch = false,
			boolOneSearch = true;

		if (0 === arFilter.length) {
			for (i = 0; i < this.offers.length; i++) {
				if (!BX.util.in_array(this.offers[i].TREE[index], arValues)) {
					arValues[arValues.length] = this.offers[i].TREE[index];
				}
			}
			boolSearch = true;
		}
		else {
			for (i = 0; i < this.offers.length; i++) {
				boolOneSearch = true;
				for (j in arFilter) {
					if (arFilter[j] !== this.offers[i].TREE[j]) {
						boolOneSearch = false;
						break;
					}
				}
				if (boolOneSearch) {
					if (!BX.util.in_array(this.offers[i].TREE[index], arValues)) {
						arValues[arValues.length] = this.offers[i].TREE[index];
					}
					boolSearch = true;
				}
			}
		}
		return (boolSearch ? arValues : false);
	};

	window.JCCatchBuyCatalogItem.prototype.GetCanBuy = function (arFilter) {
		var i = 0,
			j,
			boolSearch = false,
			boolOneSearch = true;

		for (i = 0; i < this.offers.length; i++) {
			boolOneSearch = true;
			for (j in arFilter) {
				if (arFilter[j] !== this.offers[i].TREE[j]) {
					boolOneSearch = false;
					break;
				}
			}
			if (boolOneSearch) {
				if (this.offers[i].CAN_BUY) {
					boolSearch = true;
					break;
				}
			}
		}
		return boolSearch;
	};

	window.JCCatchBuyCatalogItem.prototype.SetCurrent = function () {
		var i = 0,
			j = 0,
			arCanBuyValues = [],
			strName = '',
			arShowValues = false,
			arFilter = {},
			tmpFilter = [],
			current = this.offers[this.offerNum].TREE;

		for (i = 0; i < this.treeProps.length; i++) {
			strName = 'PROP_' + this.treeProps[i].ID;
			arShowValues = this.GetRowValues(arFilter, strName);
			if (!arShowValues) {
				break;
			}
			if (BX.util.in_array(current[strName], arShowValues)) {
				arFilter[strName] = current[strName];
			}
			else {
				arFilter[strName] = arShowValues[0];
				this.offerNum = 0;
			}
			if (this.showAbsent) {
				arCanBuyValues = [];
				tmpFilter = [];
				tmpFilter = BX.clone(arFilter, true);
				for (j = 0; j < arShowValues.length; j++) {
					tmpFilter[strName] = arShowValues[j];
					if (this.GetCanBuy(tmpFilter)) {
						arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
					}
				}
			}
			else {
				arCanBuyValues = arShowValues;
			}
			this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
		}
		this.selectedValues = arFilter;
		this.ChangeInfo();
	};

	window.JCCatchBuyCatalogItem.prototype.ChangeInfo = function () {
		var i = 0,
			j,
			index = -1,
			boolOneSearch = true;

		for (i = 0; i < this.offers.length; i++) {
			boolOneSearch = true;
			for (j in this.selectedValues) {
				if (this.selectedValues[j] !== this.offers[i].TREE[j]) {
					boolOneSearch = false;
					break;
				}
			}
			if (boolOneSearch) {
				index = i;
				break;
			}
		}
		if (-1 < index) {
			if (!!this.obPict) {
				var obData = {
					attrs: {}
				};
				if (!!this.offers[index].PICTURE_PRINT) {
					obData.attrs.src = this.offers[index].PICTURE_PRINT.SRC;
					if (this.detail) {
						obData.attrs['data-big-src'] = this.offers[index].PICTURE_PRINT.SRC_BIG;
					}
				}
				else {
					obData.attrs.src = this.defaultPict.pict.SRC;
					if (this.detail) {
						obData.attrs['data-big-src'] = this.defaultPict.pict.SRC_BIG;
					}
				}

				BX.adjust(this.obPict, obData);
				if (!!this.obPictModal) {
					obData.attrs.src = obData.attrs['data-big-src'];
					BX.adjust(this.obPictModal, obData);
				}
			}

			// photo sliders
			if (this.detail) {
				$('.thumbnails-wrap').hide();
				$('.bigimg-thumbnails-wrap').hide().find('.thumbnails-frame').removeClass('active');
			}
			else {
				$(this.obProduct).find('.photo-thumbs').hide();
			}
			var $slider = $('#' + this.visual.SLIDER_CONT_OF_ID + this.offers[index].ID);
			if ($slider.length) {
				if (!$slider.data('init')) $slider.data('init', true).find('img').each(function () {
					this.src = $(this).data('src');
					$(this).removeData('src')
				});
				if (this.detail) {
					$('.product-photos, .modal_big-img').removeClass('no-thumbs');
					$slider.show().addClass('active').find('.thumbnails-frame').addClass('active').sly('reload');
					var $sliderModal = $('#' + this.visual.SLIDER_MODAL_CONT_OF_ID + this.offers[index].ID);
					if ($sliderModal.length) {
						if (!$sliderModal.data('init')) $sliderModal.data('init', true).find('img').each(function () {
							this.src = $(this).data('src');
							$(this).removeData('src')
						});
						$sliderModal.show().find('.thumbnails-frame').addClass('active').sly('reload');
					}
				}
				else {
					$slider.show();
					initPhotoThumbs($(this.obProduct));
				}
			}
			else if (this.detail) {
				$('.product-photos, .modal_big-img').addClass('no-thumbs');
			}

			if (this.detail && !!this.obPictFly) {
				var obData = {
					attrs: {}
				};
				if (!!this.offers[index].PICTURE_PRINT) {
					obData.attrs.src = this.offers[index].PICTURE_PRINT.SRC_FLY;
				}
				else {
					obData.attrs.src = this.defaultPict.pict.SRC_FLY;
				}

				BX.adjust(this.obPictFly, obData);
			}

			if (this.secondPict && !!this.obSecondPict) {
				if (!!this.offers[index].PREVIEW_PICTURE_SECOND) {
					BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url(' + this.offers[index].PREVIEW_PICTURE_SECOND.SRC + ')'}});
				}
				else if (!!this.offers[index].PREVIEW_PICTURE.SRC) {
					BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url(' + this.offers[index].PREVIEW_PICTURE.SRC + ')'}});
				}
				else if (!!this.defaultPict.secondPict) {
					BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url(' + this.defaultPict.secondPict.SRC + ')'}});
				}
				else {
					BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url(' + this.defaultPict.pict.SRC + ')'}});
				}
			}
			if (this.showSkuProps && !!this.obSkuProps) {
				if (0 === this.offers[index].DISPLAY_PROPERTIES.length) {
					BX.adjust(this.obSkuProps, {style: {display: 'none'}, html: ''});
				}
				else {
					BX.adjust(this.obSkuProps, {style: {display: ''}, html: this.offers[index].DISPLAY_PROPERTIES});
				}
			}

			if (!!this.visual.DETAIL_LINK_CLASS && typeof this.offers[index].URL !== 'undefined' && this.offers[index].URL.length) {
				$(this.obProduct).find('.' + this.visual.DETAIL_LINK_CLASS).attr('href', this.offers[index].URL);
			}

			if (this.detail && !!this.obBuyOneClickBtn) {
				$(this.obBuyOneClickBtn).data('id', this.offers[index].ID);
			}
			if (this.detail && !this.quickView && !!this.offers[index].URL) {
				RZB2.ajax.setLocation(this.offers[index].URL);
			}
			this.setPrice(this.offers[index].PRICE);
			this.offerNum = index;
			this.QuantitySet(this.offerNum);

			BX.onCustomEvent('onCatalogStoreProductChange', [this.offers[this.offerNum].ID]);
		}
	};

	window.JCCatchBuyCatalogItem.prototype.setPrice = function (price) {
		var strPrice,
			obData;

		if (!!this.obPrice) {
			$(this.obPrice).find('.value').html(BX.Currency.currencyFormat(price.DISCOUNT_VALUE, price.CURRENCY, false));
			if (this.showOldPrice && (price.DISCOUNT_VALUE !== price.VALUE) && !!this.obPriceOld) {
				$(this.obPriceOld).find('.value').html(BX.Currency.currencyFormat(price.VALUE, price.CURRENCY, false));
			}

			if (this.showPercent) {
				if (price.DISCOUNT_VALUE !== price.VALUE) {
					obData = {
						style: {
							display: ''
						},
						html: price.DISCOUNT_DIFF_PERCENT
					};
				}
				else {
					obData = {
						style: {
							display: 'none'
						},
						html: ''
					};
				}
				if (!!this.obDscPerc) {
					BX.adjust(this.obDscPerc, obData);
				}
				if (!!this.obSecondDscPerc) {
					BX.adjust(this.obSecondDscPerc, obData);
				}
			}
		}
	};

	window.JCCatchBuyCatalogItem.prototype.Compare = function () {
		var compareParams, compareLink;

		if ($(this.obCompare).hasClass('toggled')) {
			compareLink = this.compareData.compareUrlDel;
			this.compareData.Added = false;
		}
		else {
			compareLink = this.compareData.compareUrl;
			this.compareData.Added = true;
		}
		if (!!compareLink) {
			var itemId = '';
			switch (this.productType) {
				case 1://product
				case 2://set
				case 3://sku
					itemId = this.product.id;
					break;
				// case 3://sku
				// compareLink = compareLink.replace('#ID#', this.offers[this.offerNum].ID);
				// break;
			}

			if (this.compareData.Added) {
				//added
				RZB2.ajax.Compare.ElementsList[itemId] = itemId;
			}
			else {
				//deleted
				if (typeof RZB2.ajax.Compare.ElementsList[itemId] !== 'undefined')
					delete RZB2.ajax.Compare.ElementsList[itemId];
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

	window.JCCatchBuyCatalogItem.prototype.CompareResult = function (result) {
		var popupContent, popupButtons, popupTitle;
		if (!!this.obPopupWin) {
			this.obPopupWin.close();
		}
		if (typeof result !== 'object') {
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

		if (result.STATUS === 'OK') {
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
		else {
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

	window.JCCatchBuyCatalogItem.prototype.CompareRedirect = function () {
		if (!!this.compareData.comparePath) {
			location.href = this.compareData.comparePath;
		}
		else {
			this.obPopupWin.close();
		}
	};

	window.JCCatchBuyCatalogItem.prototype.InitBasketUrl = function () {
		this.basketUrl = (this.basketMode === 'ADD' ? this.basketData.add_url : this.basketData.buy_url);
		switch (this.productType) {
			case 1://product
			case 2://set
				this.basketUrl = this.basketUrl.replace('#ID#', this.product.id.toString());
				break;
			case 3://sku
				if (this.skuSimple) {
					if (this.selectedOfferId) {
						this.basketUrl = this.basketUrl.replace('#ID#', this.selectedOfferId);
					}
				}
				else {
					this.basketUrl = this.basketUrl.replace('#ID#', this.offers[this.offerNum].ID);
				}
				break;
		}
		this.basketParams = {
			'ajax_basket': 'Y'
		};
		if (!!this.REQUEST_URI) {
			this.basketParams['REQUEST_URI'] = this.REQUEST_URI;
		}
		if (!!this.SCRIPT_NAME) {
			this.basketParams['SCRIPT_NAME'] = this.SCRIPT_NAME;
		}
		this.basketParams['IBLOCK_ID'] = this.product['IBLOCK_ID'];

		if (this.showQuantity) {
			if (this.skuSimple) {
				var target = $(BX.proxy_context);
				var quantity = target.siblings('.quantity-counter').find('input').val();
				if (quantity) {
					this.basketParams[this.basketData.quantity] = quantity;
				}
			}
			else if (!!this.obQuantity) {
				this.basketParams[this.basketData.quantity] = this.obQuantity.value;
			}
		}
		if (!!this.basketData.sku_props) {
			this.basketParams[this.basketData.sku_props_var] = this.basketData.sku_props;
		}
	};

	window.JCCatchBuyCatalogItem.prototype.FillBasketProps = function () {
		if (!this.visual.BASKET_PROP_DIV) {
			return;
		}
		var
			i = 0,
			propCollection = null,
			foundValues = false,
			obBasketProps = null;

		if (this.basketData.useProps && !this.basketData.emptyProps) {
			if (!!this.obPopupWin && !!this.obPopupWin.contentContainer) {
				obBasketProps = this.obPopupWin.contentContainer;
			}
		}
		else {
			obBasketProps = BX(this.visual.BASKET_PROP_DIV);
		}
		if (!!obBasketProps) {
			propCollection = obBasketProps.getElementsByTagName('select');
			if (!!propCollection && !!propCollection.length) {
				for (i = 0; i < propCollection.length; i++) {
					if (!propCollection[i].disabled) {
						switch (propCollection[i].type.toLowerCase()) {
							case 'select-one':
								this.basketParams[propCollection[i].name] = propCollection[i].value;
								foundValues = true;
								break;
							default:
								break;
						}
					}
				}
			}
			propCollection = obBasketProps.getElementsByTagName('input');
			if (!!propCollection && !!propCollection.length) {
				for (i = 0; i < propCollection.length; i++) {
					if (!propCollection[i].disabled) {
						switch (propCollection[i].type.toLowerCase()) {
							case 'hidden':
								this.basketParams[propCollection[i].name] = propCollection[i].value;
								foundValues = true;
								break;
							case 'radio':
								if (propCollection[i].checked) {
									this.basketParams[propCollection[i].name] = propCollection[i].value;
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
		if (!foundValues) {
			this.basketParams[this.basketData.props] = [];
			this.basketParams[this.basketData.props][0] = 0;
		}
	};

	window.JCCatchBuyCatalogItem.prototype.Add2Basket = function () {
		this.basketMode = 'ADD';
		this.Basket();
	};

	window.JCCatchBuyCatalogItem.prototype.BuyBasket = function () {
		this.basketMode = 'BUY';
		this.Basket();
	};

	window.JCCatchBuyCatalogItem.prototype.SendToBasket = function () {
		if (!this.canBuy) {
			return;
		}
		this.InitBasketUrl();
		this.FillBasketProps();
		BX.ajax.loadJSON(
			this.basketUrl,
			this.basketParams,
			BX.delegate(this.BasketResult, this)
		);
	};

	window.JCCatchBuyCatalogItem.prototype.Basket = function () {
		var contentBasketProps = '';
		if (!this.canBuy) {
			return;
		}
		switch (this.productType) {
			case 1://product
			case 2://set
				// TODO: create add with props
				/*
				 if (this.basketData.useProps && !this.basketData.emptyProps)
				 {
				 this.InitPopupWindow();
				 this.obPopupWin.setTitleBar({
				 content: BX.create('div', {
				 style: { marginRight: '30px', whiteSpace: 'nowrap' },
				 text: BX.message('TITLE_BASKET_PROPS')
				 })
				 });
				 if (BX(this.visual.BASKET_PROP_DIV))
				 {
				 contentBasketProps = BX(this.visual.BASKET_PROP_DIV).innerHTML;
				 }
				 this.obPopupWin.setContent(contentBasketProps);
				 this.obPopupWin.setButtons([
				 new BasketButton({
				 ownerClass: this.obProduct.parentNode.parentNode.className,
				 text: BX.message('BTN_MESSAGE_SEND_PROPS'),
				 events: {
				 click: BX.delegate(this.SendToBasket, this)
				 }
				 })
				 ]);
				 this.obPopupWin.show();
				 }
				 else
				 {
				 this.SendToBasket();
				 }
				 */
				this.SendToBasket();
				break;
			case 3://sku
				if (this.skuSimple) {
					var target = $(BX.proxy_context);
					if (target.data('offer-id') > 0) {
						this.selectedOfferId = target.data('offer-id');
						this.SendToBasket();
					}
					else {
						this.selectedOfferId = false;
						this.ScrollToSkuTable();
					}
				}
				else {
					this.SendToBasket();
				}
				break;
		}
	};

	window.JCCatchBuyCatalogItem.prototype.BasketResult = function (arResult) {
		var strContent = '',
			strPict = '',
			successful,
			buttons = [];

		if (!!this.obPopupWin) {
			this.obPopupWin.close();
		}
		if ('object' !== typeof arResult) {
			return false;
		}
		successful = (arResult.STATUS === 'OK');
		if (successful && this.basketAction === 'BUY') {
			this.BasketRedirect();
		}
		else {
			//this.InitPopupWindow();
			if (successful) {
				// RZB2.ajax.showMessage((!!arResult.MESSAGE ? arResult.MESSAGE : BX.message('BITRONIC2_BASKET_SUCCESS')), 'success');
				//RZB2.ajax.BasketSmall.Refresh();

				// CHANGE TO YOUR FUNCTION
				window.location.reload();
			}
			else {
				//RZB2.ajax.showMessage((!!arResult.MESSAGE ? arResult.MESSAGE : BX.message('BITRONIC2_BASKET_UNKNOWN_ERROR')), 'fail');
				// CHANGE TO YOUR FUNCTION
				window.location.reload();
			}
		}
	};

// TODO window.JCCatalogElement.prototype.incViewedCounter

	window.JCCatchBuyCatalogItem.prototype.BasketRedirect = function () {
		location.href = (!!this.basketData.basketUrl ? this.basketData.basketUrl : BX.message('BASKET_URL'));
	};

	window.JCCatchBuyCatalogItem.prototype.ScrollToSkuTable = function () {
		if (this.obSkuTable) {
			$('html,body').animate({scrollTop: $(this.obSkuTable).offset().top - 60}, 800);
		}
	};

	window.JCCatchBuyCatalogItem.prototype.InitPopupWindow = function () {
		if (!!this.obPopupWin) {
			return;
		}
		this.obPopupWin = BX.PopupWindowManager.create('CatalogSectionBasket_' + this.visual.ID, null, {
			autoHide: false,
			offsetLeft: 0,
			offsetTop: 0,
			overlay: true,
			closeByEsc: true,
			titleBar: true,
			closeIcon: {top: '10px', right: '10px'}
		});
	};

	window.JCCatchBuyCatalogItem.prototype.getUrlVars = function () {
		var $_GET = {};
		var __GET = window.location.search.substring(1).split("&");
		for (var i = 0; i < __GET.length; i++) {
			var getVar = __GET[i].split("=");
			$_GET[getVar[0]] = typeof(getVar[1]) == "undefined" ? "" : getVar[1];
		}
		return $_GET;
	};
	window.JCCatchBuyCatalogItem.Vote = {
		trace_vote: function (my_div, flag) {
			if (flag)
				while ($(my_div).length > 0) {
					$(my_div).addClass('star-over');
					my_div = $(my_div).prev();
				}
			else
				while ($(my_div).length > 0) {
					$(my_div).removeClass('star-over');
					my_div = $(my_div).prev();
				}
		},

		do_vote: function (div, arParams, e) {
			e = e || window.event;
			e.preventDefault();
			e.stopPropagation();

			this.parentObj = $(div).parent('.rating-stars');

			var vote_id = $(div).data('id');
			var vote_value = $(div).data('value');

			arParams['vote'] = 'Y';
			arParams['vote_id'] = vote_id;
			arParams['rating'] = vote_value;

			BX.ajax({
				timeout: 30,
				method: 'POST',
				dataType: 'json',
				url: '/bitrix/components/bitrix/iblock.vote/component.php',
				data: arParams,
				onsuccess: BX.delegate(this.SetResult, this)
			});
		},

		SetResult: function (result) {
			this.parentObj.removeClass('r0 r1 r2 r3 r4 r5').addClass('r' + Number(result.value));
			this.parentObj.find('i.flaticon-black13').removeAttr('onclick');

			this.parentObj.siblings().find('span.review-number').empty().html(result.votes);
		}
	}
})(window);