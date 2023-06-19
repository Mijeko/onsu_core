window.JCCatalogSectionAll = function (arParams)
{
	this.showAbsent = true;
	this.showOldPrice = false;
	this.selectedValues = {};
	this.offers = [];
	this.obBuyBtn = null;
	this.obTreeRows = [];
	this.obPrice = null;
	this.obPict = null;
	
	this.defaultPict = {
		pict: null,
		smallPict: null
	};
	this.visual = {
		ID: ''
	};
	this.product = {
		name: '',
		pict: {},
		id: 0
	};
	
	this.errorCode = 0;
	if ('object' === typeof arParams)
	{
		this.productType = parseInt(arParams.PRODUCT_TYPE, 10);
		this.visual = arParams.VISUAL;
		this.showAbsent = arParams.SHOW_ABSENT;
		this.showOldPrice = !!arParams.SHOW_OLD_PRICE;
		switch (this.productType)
		{
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
						if (!!arParams.DEFAULT_PICTURE.PICTURE_SMALL)
							this.defaultPict.smallPict = arParams.DEFAULT_PICTURE.PICTURE_SMALL;
					}
				}
				else
				{
					this.errorCode = -1;
				}
				break;
			default:
				this.errorCode = -1;
		}
	}
	if (0 === this.errorCode)
	{
		BX.ready(BX.delegate(this.Init,this));
	}
}

window.JCCatalogSectionAll.prototype.Init = function()
{
	var i = 0,
		strPrefix = '',
		TreeItems = null;
	this.obPrice = BX.findChildren(BX(this.visual.ID),{'class':'price'}, true);
	if (!this.obPrice)
	{
		this.errorCode = -16;
	}
	this.obPict = BX.findChildren(BX(this.visual.ID),{'tag':'img', 'class' : 'product_photo' }, true);
	if (!this.obPict)
	{
		this.errorCode = -2;
	}
	if (!!this.visual.BUY_ID)
	{
		this.obBuyBtn = BX.findChildren(BX(this.visual.ID), {'attr': {'id': this.visual.BUY_ID}}, true)[0];
	}
	if (3 === this.productType)
	{
		if (!!this.visual.TREE_ID)
		{
			this.obTree = BX.findChildren(BX(this.visual.ID), {'attr': {'id': this.visual.TREE_ID}}, true)[0];
			if (!this.obTree)
			{
				this.errorCode = -256;
			}
			strPrefix = this.visual.TREE_ITEM_ID;
			for (i = 0; i < this.treeProps.length; i++)
			{
				this.obTreeRows[i] = {
					LIST: BX.findChildren(BX(this.visual.ID), {'attr': {'id': strPrefix+this.treeProps[i].ID+'_list'}}, true)[0],
					CONT: BX.findChildren(BX(this.visual.ID), {'attr': {'id': strPrefix+this.treeProps[i].ID+'_cont'}}, true)[0]
				};	
				if (/*!this.obTreeRows[i].LEFT || !this.obTreeRows[i].RIGHT || */!this.obTreeRows[i].LIST || !this.obTreeRows[i].CONT)
				{
					this.errorCode = -512;
					break;
				}
			}
		}
	}
	if (0 === this.errorCode)
	{
		switch (this.productType)
		{
			case 3://sku
				
				for (i = 0; i < this.obTreeRows.length; i++)
				{
					//BX.bind(this.obTreeRows[i].LIST, 'change', BX.delegate(this.SelectOfferProp, this));
					$(this.obTreeRows[i].LIST).on('change', BX.delegate(this.SelectOfferProp, this));
				}
				this.SetCurrent();
				break;
		}
	}
};

window.JCCatalogSectionAll.prototype.GetCanBuy = function(arFilter)
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

window.JCCatalogSectionAll.prototype.SetCurrent = function()
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

window.JCCatalogSectionAll.prototype.ChangeInfo = function()
{
	var i = 0,
		j,
		index = -1,
		obData = {},
		boolOneSearch = true,
		strPrice = '';

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
		if (!!this.obPict)
		{
			if (!!this.offers[index].PREVIEW_PICTURE)
			{
				if(this.obPict.length > 1)
				{
					BX.adjust(this.obPict[1], {attrs: {src: this.offers[index].PREVIEW_PICTURE_SMALL}});
					BX.adjust(this.obPict[0], {attrs: {src: this.offers[index].PREVIEW_PICTURE}});
				}
				else if(this.obPict.length == 1)	
					BX.adjust(this.obPict[0], {attrs: {src: this.offers[index].PREVIEW_PICTURE_SMALL}});
			}
			else
			{
				if(this.obPict.length > 1)
				{
					BX.adjust(this.obPict[1], {attrs: {src: this.defaultPict.smallPict}});
					BX.adjust(this.obPict[0], {attrs: {src: this.defaultPict.pict}});
				}
				else if(this.obPict.length == 1)	
					BX.adjust(this.obPict[0], {attrs: {src: this.defaultPict.smallPict}});
			}
		}
		if (this.showSkuProps && !!this.obSkuProps)
		{
			if (0 === this.offers[index].DISPLAY_PROPERTIES.length)
			{
				BX.adjust(this.obSkuProps, {style: {display: 'none'}, html: ''});
			}
			else
			{
				BX.adjust(this.obSkuProps, {style: {display: ''}, html: this.offers[index].DISPLAY_PROPERTIES});
			}
		}
		if (!!this.obPrice)
		{
			for(i = 0; i < this.obPrice.length; i++)
			{
				if(BX.findChild(this.obPrice[i], {'class':'rubl'}, true))
				{
					strRubSymbol = BX.findChild(this.obPrice[i], {'class':'rubl'}, true).outerHTML;
					strPrice = this.offers[index].PRICE.PRINT_DISCOUNT_VALUE.replace(/[^\d\s,]/g, '') + strRubSymbol;
					if (this.showOldPrice && (this.offers[index].PRICE.DISCOUNT_VALUE !== this.offers[index].PRICE.VALUE))
					{
						strPrice += ' <span class="oldprice">'+this.offers[index].PRICE.PRINT_VALUE.replace(/[^\d\s,]/g, '') + strRubSymbol +'</span>';
					}
				}
				else
				{
					strPrice = this.offers[index].PRICE.PRINT_DISCOUNT_VALUE;
					if (this.showOldPrice && (this.offers[index].PRICE.DISCOUNT_VALUE !== this.offers[index].PRICE.VALUE))
					{
						strPrice += ' <span class="oldprice">'+this.offers[index].PRICE.PRINT_VALUE +'</span>';
					}
				}
				BX.adjust(this.obPrice[i], {html: strPrice});
			}
			
			if (this.showPercent)
			{
				if (this.offers[index].PRICE.DISCOUNT_VALUE !== this.offers[index].PRICE.VALUE)
				{
					obData = {
						style: {
							display: ''
						},
					html: this.offers[index].PRICE.DISCOUNT_DIFF_PERCENT
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
		if (!!this.obBuyBtn && this.offers[index].ID > 0)
		{
			obData = {
				attrs: { 'data-sku-id': this.offers[index].ID}
			};
			BX.removeClass(this.obBuyBtn, 'button_in_basket');
			BX.adjust(this.obBuyBtn, obData);
		}
		this.offerNum = index;
	}
};

window.JCCatalogSectionAll.prototype.SelectOfferProp = function()
{
	var i = 0,
		value = '',
		strTreeValue = '',
		arTreeItem = [],
		RowItems = null,
		target = BX.proxy_context.options[BX.proxy_context.selectedIndex];

	if (!!target && target.hasAttribute('data-treevalue'))
	{
		strTreeValue = target.getAttribute('data-treevalue');
		arTreeItem = strTreeValue.split('_');
		if (this.SearchOfferPropIndex(arTreeItem[0], arTreeItem[1]))
		{
			RowItems = BX.findChildren(target.parentNode, {tagName: 'option'}, false);
			if (!!RowItems && 0 < RowItems.length)
			{
				for (i = 0; i < RowItems.length; i++)
				{
					value = RowItems[i].getAttribute('data-onevalue');
					if (value === arTreeItem[1])
					{
						BX.addClass(RowItems[i], 'bx_active');
					}
					else
					{
						BX.removeClass(RowItems[i], 'bx_active');
					}
				}
				
				$(target).selectBox('refresh');
			}
		}
	}
};

window.JCCatalogSectionAll.prototype.SearchOfferPropIndex = function(strPropID, strPropValue)
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
			else
			{
				arFilter[strName] = arCanBuyValues[0];
			}
			this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
		}
		this.selectedValues = arFilter;
		this.ChangeInfo();
	}
	return true;
};

window.JCCatalogSectionAll.prototype.UpdateRow = function(intNumber, activeID, showID, canBuyID)
{
	var i = 0,
		showI = 0,
		value = '',
		countShow = 0,
		obData = {},
		pictMode = false,
		isCurrent = false,
		selectIndex = 0,
		currentShowStart = 0,
		RowItems = null;

	if (-1 < intNumber && intNumber < this.obTreeRows.length)
	{
		RowItems = BX.findChildren(this.obTreeRows[intNumber].LIST, {tagName: 'option'}, false);
		
		if (!!RowItems && 0 < RowItems.length)
		{
			countShow = showID.length;
			obData = {
				props: { className: '' },
				style: { },
				attrs: { selected: ''}
			};
			for (i = 0; i < RowItems.length; i++)
			{
				value = RowItems[i].getAttribute('data-onevalue');
				isCurrent = (value === activeID);
				
				if (BX.util.in_array(value, canBuyID))
				{
					obData.props.className = (isCurrent ? 'bx_active' : '');
					obData.attrs.selected = (isCurrent ? 'selected' : '');
				}
				else
				{
					obData.props.className = (isCurrent ? 'bx_active bx_missing' : 'bx_missing');
					obData.attrs.selected = '';
				}
				obData.style.display = 'none';
				obData.attrs.disabled = 'disabled';
				
				if (BX.util.in_array(value, showID))
				{
					obData.style.display = '';
					obData.attrs.disabled = '';
					if (isCurrent)
					{
						selectIndex = showI;
					}
					showI++;
				}			
				BX.adjust(RowItems[i], obData);
			}
			$(this.obTreeRows[intNumber].LIST).selectBox('refresh');
		}
	}
};

window.JCCatalogSectionAll.prototype.GetRowValues = function(arFilter, index)
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