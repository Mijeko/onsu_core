b2.init.bigBasketPage = function(){
	new UmTabs('.um_tab', {
		onChange: function(target){
            b2.init.selects && b2.init.selects($('select:visible').parent());
            if (typeof b2.init.scrollbarsTargeted == "function") b2.init.scrollbarsTargeted($('select:visible').parent());
		}
	});
}