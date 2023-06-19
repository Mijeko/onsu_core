(function(window) {
    'use strict';
    if (typeof BX != 'undefined') {
        BX.addCustomEvent("onCatalogStoreProductChange", BX.delegate(function (offerid) {
            $('.rz_oneclick-buy input[name="ELEMENT_ID"]').val(offerid);
        }, this));
    }
})(window);
