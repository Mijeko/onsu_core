if (typeof window.RZB2 == "undefined") {
	RZB2 = {utils: {}};
}

if (typeof RZB2.utils == "undefined") {
	RZB2.utils = {};
}

RZB2.utils.cookiePrefix = 'RZ_';
RZB2.utils.setCookie = function(name, value, prefix)
{
	var date = new Date();
	date.setFullYear(date.getFullYear() + 1);

	prefix = prefix || this.cookiePrefix;
	document.cookie = prefix + name + '=' + value + '; path=/; expires=' + date.toUTCString();
}

RZB2.utils.getCookie = function(name, prefix)
{
	prefix = prefix || this.cookiePrefix;
	name = prefix + name;
	var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	))
	return matches ? decodeURIComponent(matches[1]) : undefined
}

RZB2.utils.deleteCookie = function(name)
{
	name = this.cookiePrefix + name;
	this.setCookie(name, null, { expires: -1 })
}

RZB2.utils.getQueryVariable = function (variable, query, remove) {
	if (!query) {
		query = window.location.search.substring(1);
	} else {
		query = query.split('?')[1];
		if (!query) {
			return [];
		}
	}
	var result = {};
	if (query.length > 0) {
		var vars = query.split("&");

		for (var i = 0; i < vars.length; i++) {
			var pair = vars[i].split("=");
			if (variable && pair[0] == variable) {
				return pair[1];
			}
			if (typeof(remove) != 'undefined'
				&& pair[0] in remove) {
				continue;
			}
			result[pair[0]] = pair[1];
		}
	}
	return (result);
};

RZB2.utils.initLazy = function($container){
    $container.find("img.lazy").lazyload({
        effect: "fadeIn",
        threshold: 1000,
        failurelimit: 10000
    }).removeClass('lazy');
};
RZB2.utils.addFuncToReady = function(funcName, element,params){
    if (Array.isArray(funcName)){
        for (var i=0; i < funcName.length; i++){
            RZB2.utils.readyDocument(funcName[i],element,params);
        }
    } else{
        RZB2.utils.readyDocument(funcName,element,params);
    }
};
RZB2.utils.readyDocument = function(func,el,params){
    if (typeof window.frameCacheVars !== "undefined")
    {
        BX.addCustomEvent("onFrameDataReceived", function (json){
            jQuery( document ).ready( function() {
                RZB2.utils.callFunc(func,el,params);
            });
        });
    } else {
        jQuery( document ).ready( function() {
            RZB2.utils.callFunc(func,el,params);
        });
    }
};

RZB2.utils.callFunc = function(func,el,params){
    if (typeof func == 'function'){
        params ? func(params) : func();
    }else if(typeof func == 'string') {
        if ($(el).length) {
            params ? $(el)[func](params) : $(el)[func](params) ;
        }else{
            params ? window[func](params) : window[func]();
        }
    }
};
