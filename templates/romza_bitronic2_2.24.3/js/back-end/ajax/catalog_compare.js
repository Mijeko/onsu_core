//Compare Page
RZB2.ajax.ComparePage = {
	SendRequest: function(sender, params, callback){
		if (typeof params == "function" && typeof callback == "undefined") {
			callback = params;
			params = undefined;
		}
		var data = params || {};
		for (var key in RZB2.ajax.params) {
			data[key] = RZB2.ajax.params[key];
		}
		if (!!sender) {
			var href = $(sender).attr('href');
			if (!!href) {
				data['REQUEST_URI'] = href;
				var uriParams = RZB2.utils.getQueryVariable(null, href);
				for (var key in uriParams) {
					data[key] = uriParams[key];
				}
			}
		}
		data['rz_ajax'] = 'y';
		if (typeof callback != "function") {
			callback = null;
		}
		$.ajax({
			type: "POST",
			url: SITE_DIR + 'ajax/catalog.php',
			data: data,
			dataType: "html",
			error: function(){
				window.location.assign(data['REQUEST_URI']);
			},
			success: callback,
		});
	}
};