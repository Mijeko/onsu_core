function RZB2_initDetailHandlers($){

	var $body = $('body');

	// CATALOG ELEMENT - EDOST
	$('.calc-delivery').on('click', function(e){
		var _ = $(this);
		e.preventDefault();
		edost_catalogdelivery_show(_.data('id'), _.data('name'));
	});
	var $edost = $('#edost_catalogdelivery_inside');
	if (typeof edost_RunScript == "function") {
		if (typeof YS == "object" && typeof YS.GeoIP == "object" && typeof YS.GeoIP.Cookie == "object") {
			var locID = parseInt(YS.GeoIP.Cookie.getLocationID('YS_GEO_IP_LOC_ID'));
			if (locID > 0) {
				edost_catalogdelivery.set_cookie('edost_location='+locID+'||');
			}
		}
		if ($edost.length > 0) {
			edost_RunScript('preview', $edost.data('id'), $edost.data('name'));
		}
	}

	// REVIEWS
	var reviewCaptcha = function(){
		var $captcha = $('#comments').find('div.captcha');
		var $captchaImg = $captcha.find('img');
		if ($captchaImg.length < 1) {
			$captcha.html('<img src="/bitrix/tools/captcha.php?captcha_code=' + $captcha.data('code') + '" alt="CAPTCHA">');
		}
		$('.combo-target.comments, .write-review_top').off('click.captcha');
	};
	$('.combo-target.comments').on('click', '.form-wrap>header', reviewCaptcha);
	$('.write-review_top').on('click.captcha', reviewCaptcha);
	RZB2.ajax.Review.Init();
	//Blog
	$('#comments').on('submit', '#form_comment_blog', function (e) {
		e.preventDefault();
		var $this = $(this),
			data = $this.serializeArray();
		RZB2.ajax.Review.Blog.SendRequest($this, data, false);
	});
	$('#comments').on('click', '#blog_comments .pagination a', function (e) {
		e.preventDefault();
		var $this = $(this);
		if(!$this.hasClass('active')) 
		{
			RZB2.ajax.Review.Blog.ChangePage($this, $this.data('page'));
		}
	});
	
	//Forum
	$('#comments').on('submit', '#form_comment_forum', function (e) {
		e.preventDefault();
		var $this = $(this),
			data = $this.serializeArray();
		RZB2.ajax.Review.Forum.SendRequest(data);
	});
	$('#comments').on('click', '#forum_comments .pagination a', function (e) {
		e.preventDefault();
		var $this = $(this);
		if(!$this.hasClass('active') || !$this.hasClass('disabled')) 
		{
			RZB2.ajax.Review.Forum.ChangePage($this.data('page'), $this.data('pagen-key'));
		}
	});
	
	// CATALOG SET CONSTRUCTOR
	$('.product-page').on('click', '.collection-wrap .btn-main', function(e){
		e.preventDefault();
		RZB2.ajax.CatalogSetConstructor.AddToBasket(this);
	});
	
	$('.product-page').on('click', '.custom-collection', function(e){
		e.preventDefault();
		var setId = 'SetConstructor'+$(this).data('id');
		if(typeof RZB2.ajax[setId] !== 'undefined')
		{
			RZB2.ajax[setId].Load();
		}
	});

	// DETAIL MODALS - CRY FOR PRICE, PRICE_DROPS, EXIST_PRODUCT
	var $doc = $(document);

	var $mcfp = $('#modal_cry-for-price');
	if ($mcfp.length > 0) {
		$mcfp.on('shown.bs.modal', function (e) {
			var $btn = $(e.relatedTarget);
			var data = [
				{'name': 'PRICE', 'value': $btn.data('price')},
				{'name': 'CURRENCY', 'value': $btn.data('currency')},
				{'name': 'PRODUCT', 'value': $btn.data('product')},
				{'name': 'PRICE_TYPE', 'value': $btn.data('price_type')}
			];
			$(this).find('form').rise_modal(data);
		});
	}
	$doc.on('submit', '.form_cry-for-price', function (e) {
		var $this = $(this);
		if (!formCheck($this)) {
			return false;
		} else {
			e.preventDefault();
			$this.send_modal();
			return false;
		}
	});
	var $miwpd = $('#modal_inform-when-price-drops');

	var initPriceForm = function () {
        var moneyFormat = wNumb({
            mark: '.',
            thousand: ' ',
            decimals: 2
        });
        var thisModal = $('#modal_inform-when-price-drops');
        var currentPriceField = $('#price-current').children('.value');
        var currentPrice = moneyFormat.from(currentPriceField.html());
        thisModal.on('show.bs.modal', function () {
            currentPrice = moneyFormat.from(currentPriceField.html());
        });

        var $slider = $('.desired-price-slider'),
            $inputs = $('#desired-price>.value, #modal_inform-when-price-drops_price');

        var desiredPriceField = $('#desired-price').children('.value');
        var priceDifferenceField = $('#price-difference').children('.value');
        var priceDifferencePercentField = $('#price-difference').children('.percent-value');

        var desiredPrice = moneyFormat.from(desiredPriceField.html());
        var priceDifference = currentPrice - desiredPrice;
        var priceDifferencePercent = Number((priceDifference / currentPrice) * 100).toFixed(2);

        function setDifference() {
            priceDifferenceField.html(moneyFormat.to(priceDifference));
            priceDifferencePercentField.html('(' + priceDifferencePercent + '%)');
        }

        noUiSlider.create($slider.get(0), {
            start: currentPrice * 0.9,
            connect: "lower",
            step: 1,
            range: {
                'min': 1,
                'max': currentPrice
            },
            format: moneyFormat
        }).on('update', function (values, handle) {
            $inputs.val(values[0]);
            desiredPrice = moneyFormat.from(values[0]);
            priceDifference = currentPrice - desiredPrice;
            priceDifferencePercent = Number((priceDifference / currentPrice) * 100).toFixed(2);
            setDifference();
        });

        $inputs.on('change', function(){
            $slider.get(0).noUiSlider.set(this.value);
        });
    }

	if ($miwpd.length > 0) {
		$miwpd.on('shown.bs.modal', function (e) {
			var $form = $(this).find('form');
			var $btn = $(e.relatedTarget);
			var data = [
				{'name': 'PRICE', 'value': $btn.data('price')},
				{'name': 'CURRENCY', 'value': $btn.data('currency')},
				{'name': 'PRODUCT', 'value': $btn.data('product')},
				{'name': 'PRICE_TYPE', 'value': $btn.data('price_type')}
			];
			$.when($form.rise_modal(data)).then(initPriceForm);
		});
	}

	$doc.on('keypress', '#modal_inform-when-price-drops_price', function (e) {
		if (e.which !== 13) return true;
		$(this).change();
		return false;
	});
	$doc.on('keypress', '#modal_inform-when-price-drops_email', function (e) {
		if (e.which !== 13) return true;
		$('#modal_inform-when-price-drops_price').focus();
		return false;
	});
	$doc.on('submit', '.form_inform-when-price-drops', function (e) {
		var $this = $(this);
		if (!formCheck($this)) {
			return false;
		} else {
			e.preventDefault();
            $.when($this.send_modal()).then(initPriceForm);
			return false;
		}
	});

	$('button.view3d').on('click', function(){
		$('div.view3d button').trigger('click');
		$('.3dreview').trigger('click');
	});
}

if (typeof domReady != "undefined" && domReady == true) {
	RZB2_initDetailHandlers(jQuery);
} else {
	jQuery(document).ready( RZB2_initDetailHandlers );
}
