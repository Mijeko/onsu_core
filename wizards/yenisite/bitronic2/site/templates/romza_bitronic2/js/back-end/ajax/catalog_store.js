function JCCatalogStoreSKU(arParams)
{
    if(!arParams) return;

    this.offers = arParams.OFFERS;
    this.CONTAINER_ID = arParams.CONTAINER_ID;
    BX.addCustomEvent(window, "onCatalogStoreProductChange", BX.proxy(this.offerOnChange, this));

	var obbx =  $('#'+this.CONTAINER_ID).closest('.store-info').data('obbx') ? $('#'+this.CONTAINER_ID).closest('.store-info').data('obbx') : $('#'+this.CONTAINER_ID).data('obbx');
	obbx = obbx ? obbx : $('#'+this.CONTAINER_ID).parent().data('obbx');

	if (typeof obbx == "object" && obbx instanceof JCCatalogItem) {
		obbx.updateStores();
	}
}

JCCatalogStoreSKU.prototype.offerOnChange = function(id,initTabs)
{
	if(typeof this.offers[id] == 'undefined') return;
	
	
	var storeAmountDiv = BX(this.CONTAINER_ID);
	if(storeAmountDiv)
	{
		if (0 === this.offers[id].DISPLAY_PROPERTIES.length)
		{
			BX.adjust(storeAmountDiv, {style: {display: 'none'}, html: ''});
		}
		else
		{
			if (!$(storeAmountDiv).hasClass('availability')) {
                BX.adjust(storeAmountDiv, {style: {display: ''}, html: this.offers[id].DISPLAY_PROPERTIES});
            } else{
                BX.adjust(storeAmountDiv,{html: this.offers[id].DISPLAY_PROPERTIES});
			}
		}
	}
}

//# sourceURL=js/back-end/ajax/catalog_store.js