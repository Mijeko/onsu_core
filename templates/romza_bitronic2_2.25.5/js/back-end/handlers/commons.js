function RZB2_initCommonHandlers($){

	//COMPARE
	$('#popup_compare').on('click', '.table-wrap button.btn-delete', function(e){
		e.preventDefault();
		RZB2.ajax.Compare.Delete($(this).data('id'));
	});
	$('#popup_compare').on('click', '.popup-footer button.btn-delete', function(e){
		e.preventDefault();
		RZB2.ajax.Compare.DeleteAll();
	});
	
	//FAVORITES
	$('#popup_favorites').on('click', '.table-wrap button.btn-delete', function(e){
		e.preventDefault();
		RZB2.ajax.Favorite.Delete($(this).data('id'));
		// RZB2.ajax.Favorite.Refresh();
	});
	$('#popup_favorites').on('click', '.popup-footer button.btn-delete', function(e){
		e.preventDefault();
		RZB2.ajax.Favorite.DeleteAll();
	});
	$('#popup_favorites').on('click', '.popup-footer button.btn-main', function(e){
		e.preventDefault();
		RZB2.ajax.Favorite.ToBasket.AddAll();
	});

	// SMALL BASKET
    $('#basket').on('click','.go-to-basket', function(e){
        e = e || window.event;
        e.preventDefault();
        window.location = $(this).attr('href');
    });

	var timerQuantity, timerTimeout;
	$('#popup_basket .basket-content').on({
			'mousedown': function(e){
				e = e || window.event;
				var _ = $(this);
				var quanInput = _.siblings('.quantity-input');
				if ( quanInput.hasClass('disabled') || quanInput.is(':disabled') || _.hasClass('disabled') ) return;
				var curValue = Number(quanInput.val());
				var ratio = parseFloat(quanInput.data('ratio'));
				
				var changeStep = ratio;
				if ( _.hasClass('decrease') ) changeStep = -ratio;
				if (isNaN(curValue) || curValue<ratio) curValue = ratio;
				

				curValue += changeStep;
				if ( curValue <= ratio ) {
					curValue = ratio;
					_.addClass('disabled');
				}
				quanInput.val(Number(parseFloat(curValue).toFixed(3)));
				timerTimeout = setTimeout(function(){
					timerQuantity = setInterval(function(){
						curValue += changeStep;
						if ( curValue <= ratio ) {
							curValue = ratio;
							_.addClass('disabled');
						}
						quanInput.val(curValue);
					}, 100);
				}, 300);
				_.data('changed', true);
			},
			'mouseup mouseleave': function(e){
				clearTimeout(timerTimeout);
				clearInterval(timerQuantity);
				if (!$(this).data('changed')) return;
				
				RZB2.ajax.BasketSmall.ChangeQuantity(e);
			}
		}, 'button.quantity-change'
	).on('change','input[name=quantity]', function (e) {
			RZB2.ajax.BasketSmall.ChangeQuantity(e);
	}).on('click','button.btn-delete', function (e) {
			e = e || window.event;
			e.preventDefault();
			
			RZB2.ajax.BasketSmall.Delete(e);
	});
	$('#popup_basket .popup-footer').on('click', 'button.btn-delete', function(e) {
		e = e || window.event;
		e.preventDefault();

        $('#popup_basket').modal('hide')
		RZB2.ajax.BasketSmall.DeleteAll(e);
	});
	$('#popup_basket').on('shown.bs.modal', function(e){
		initTooltips(this);
	});

	// REGISTRATION
	$(document).on('submit', '.form_registration', function(){
		var _ = $(this);
		_.find('input[name="USER_LOGIN"]').val(_.find('input[name="USER_EMAIL"]').val());
		_.find('input[name="NEW_LOGIN"]').val(_.find('input[name="NEW_EMAIL"]').val());
	});
	$('#modal_registration').on('show.bs.modal', function(){
		if (typeof RZB2.ajax.registration != "undefined") return;

		RZB2.ajax.registration = new RZB2.ajax.FormUnified({
			ID: 'modal_registration',
			AJAX_FILE: SITE_DIR + "ajax/registration.php",
		});
		$('#modal_registration').on('shown.bs.modal', function(){
			$(this).find('.textinput').first().focus();
		});
		RZB2.ajax.registration.Load(false, function(){
			b2.init.formValidation('#modal_registration');
			initModalRegistration();
			$('#modal_registration')
			.find('.btn-form-switch').click(function(e){
				e.preventDefault();
				$(this).closest('.modal').modal('hide');
			})
			.end()
			.find('form').on('submit', function(e){
				e.preventDefault();
				if(document.getElementById('accept-agreement') != null && !document.getElementById('accept-agreement').checked){
					RZB2.ajax.showMessage($(this).find('.registration_alert').html(), 'fail');
					return false;
				}
				RZB2.ajax.registration.Post($(this), function(res){
					$("<div></div>").html(res).find('.anti-robot').replaceAll($('#modal_registration').find('.anti-robot'));
				}, false);
			});
		});
	});

	function feedbackHandler(res) {
		var $modal = $(this).closest('.modal');
		$modal.find('.content').html(res);
		if (res.indexOf("'fail'") == -1) {
			$modal.find('form').trigger('reset');
			$('#modal_success').one('hide.bs.modal', function(){
				$modal.modal('hide');
			});
		}
		if (!$modal.length){
            $modal = $(this).closest('.modal-form');
            $modal.html(res);
            $modal.find('form').trigger('reset');
		}
        RZB2.utils.initValidForms && RZB2.utils.initValidForms();
	}
	
	// CALL ME
	$('.contacts-content').on('click', '.phone-link', function(e){
			e.preventDefault();
			$('#modal_callme').modal('show');
			if(typeof RZB2.ajax.callMe === 'undefined')
			{
				RZB2.ajax.callMe = new RZB2.ajax.FormUnified({
					ID: 'modal_callme',
					AJAX_FILE: SITE_DIR + "ajax/callme.php",
				});
				RZB2.ajax.callMe.Load();
			}
	});
	$('#modal_callme,.header-contacts .modal-form').on('submit', 'form', function(e){
		e.preventDefault();
        if(typeof RZB2.ajax.callMe === 'undefined')
        {
            RZB2.ajax.callMe = new RZB2.ajax.FormUnified({
                ID: 'modal_callme',
                AJAX_FILE: SITE_DIR + "ajax/callme.php",
            });
        }
		RZB2.ajax.callMe.Post($(this), BX.delegate(feedbackHandler, this), false, false,true);
	});

	// FEEDBACK
	$(document).on('click', '.feedback-link', function (e) {
		e.preventDefault();
		$('#modal_feedback').modal('show');
		if (typeof RZB2.ajax.feedBack === 'undefined') {
			RZB2.ajax.feedBack = new RZB2.ajax.FormUnified({
				ID: 'modal_feedback',
				AJAX_FILE: SITE_DIR + "ajax/feedback.php",
				URL: location.pathname
			});
			RZB2.ajax.feedBack.Load();
		}
	});
	$('#modal_feedback').on('submit', 'form', function (e) {
		e.preventDefault();
		RZB2.ajax.feedBack.Post($(this), BX.delegate(feedbackHandler, this), false);
	});
	// FEEDBACK - CONTACT FOR PRODUCT
	$('#modal_contact_product').on('show.bs.modal', function(e){
		var $button = $(e.relatedTarget);
		if (typeof RZB2.ajax.feedbackContact === 'undefined') {
			RZB2.ajax.feedbackContact = new RZB2.ajax.FormUnified({
				ID: 'modal_contact_product',
				AJAX_FILE: SITE_DIR + 'ajax/feedback_contact.php'
			});
			RZB2.ajax.feedbackContact.UpdateJS = function(){
				$('#modal_contact_product [name="romza_feedback[PRODUCT]"]').val(RZB2.ajax.feedbackContact.productId);
				$('#modal_contact_product [name="romza_feedback[QUANTITY]"]').closest('.textinput-wrapper').after(RZB2.ajax.feedbackContact.measureHTML);
			}
		}
		RZB2.ajax.feedbackContact.Load([], function(){
			var productId = $button.data('product-id');
			var offerId;
			if (offerId = $button.data('offer-id')) {
				productId = offerId;
			}
			RZB2.ajax.feedbackContact.productId = productId;
			RZB2.ajax.feedbackContact.measureHTML = ' - <span>' + $button.data('measure-name') + '</span>';
			RZB2.ajax.feedbackContact.UpdateJS();
		});
	}).on('submit', 'form', function (e) {
		e.preventDefault();
		RZB2.ajax.feedbackContact.Post($(this), function(res){
			if (res.indexOf("'fail'") == -1) {
				$('#modal_contact_product').modal('hide');
			} else {
				$('#modal_contact_product .content').html(res);
				RZB2.ajax.feedbackContact.UpdateJS();
			}
		}, false);
	});
	// FEEDBACK - SUBSCRIBE PRODUCT
	var $msp = $('#modal_subscribe_product');
	if ($msp.length > 0) {
		$msp.on('shown.bs.modal', function (e) {
			var $btn = $(e.relatedTarget);
			var data = [
				{'name': 'PRODUCT', 'value': $btn.data('product')}
			];
			$(this).find('form').rise_modal(data);
		});
	}
	$body.on('submit', '#modal_subscribe_product_form', function (e) {
		var $this = $(this);
		if (!formCheck($this)) {
			return false;
		} else {
			e.preventDefault();
			$this.send_modal();
			return false;
		}
	});

	// MAP
	$('#modal_address-on-map').on('show.bs.modal', function(){
		if (typeof RZB2.ajax.map != "undefined") return;

		RZB2.ajax.map = new RZB2.ajax.FormUnified({
			ID: 'modal_address-on-map',
			AJAX_FILE: SITE_DIR + "ajax/map.php",
		});
		RZB2.ajax.map.Load();
	});

	// CATALOG STORES
	$(document).on('open', '.store-info.notification-popup', function(e){
		var _ = $(this);
		if (_.data('loaded') == true) return;
		_.data('loaded', true);

		var params = [
			{name: "ITEM_ID",       value: _.data('id')},
			{name: "STORE_POSTFIX", value: _.data('postfix')}
		];

		var $content = _.find('.content');
		RZB2.ajax.Stores.Load($content, params, function(res){
			_.data('spinner').Stop();
		});
	});

	RZB2_initCommonHandlers.GetStoreContent = function(container,id,postfix){
        var _ = $(container);
        var params = [
            {name: "ITEM_ID",       value: id},
            {name: "STORE_POSTFIX", value: postfix}
        ];

        var $content = _;
        var spinner = RZB2.ajax.spinner(_);
        spinner.Start(smallSpinnerParams);
        RZB2.ajax.Stores.Load($content, params, function(res){
            if (typeof spinner == 'object') {
                spinner.Stop();
                delete spinner;
                if (b2.el.productInfoSections instanceof UmComboBlocks && $(res).siblings('.combo-target-content').length){
                    b2.el.productInfoSections.initFull();
				}
            }
        });
	};

	$('.store-info.notification-popup[data-state="shown"]').trigger('open');
	
	// QUICK VIEW
	if (b2.s.quickView == "Y"
		&& (
			typeof b2.init.catalogPage == "function"
			|| (
				typeof b2.init.homePage == "function" &&
				b2.s.blockHomeSpecials == "Y"
			)
		)
	) {
		var $modalQuickView = $('#modal_quick-view');
		if (typeof RZB2.ajax.quickView == "object") {
			$modalQuickView
				.on('shown.bs.modal',  RZB2.ajax.quickView.onModalShown)
				.on('hidden.bs.modal', RZB2.ajax.quickView.onModalHidden);
		} else {
			var quickViewInterval;
			$modalQuickView
				.on('shown.bs.modal.temp', function(e){
					var self = this;
					quickViewInterval = setInterval(function(){
						if (typeof RZB2.ajax.quickView == "undefined") return;
						RZB2.ajax.quickView.onModalShown.call(self, e);
						$modalQuickView
							.off('shown.bs.modal.temp')
							.off('hidden.bs.modal.temp')
							.on('shown.bs.modal',  RZB2.ajax.quickView.onModalShown)
							.on('hidden.bs.modal', RZB2.ajax.quickView.onModalHidden);
						clearInterval(quickViewInterval);
					}, 500);
				})
				.on('hidden.bs.modal.temp', function(){
					if (!!quickViewInterval) {
						clearInterval(quickViewInterval);
					}
				});
		}
		$modalQuickView.switchTo = function(obj) {
			if (typeof RZB2.ajax.quickView == "object") {
				RZB2.ajax.quickView.onModalHidden.call(this[0]);
			}
			this.trigger({
				type: 'shown',
				relatedTarget: $(obj).find('.quick-view-switch').get(0)
			});
		};
		$modalQuickView
			.on('show.bs.modal', function(e){
				var newHash = '#qv_' + $(e.relatedTarget).closest('.catalog-item-wrap').attr('id');
				if (newHash != document.location.hash) {
					RZB2.ajax.setLocation(newHash);
				}
			})
			.on('shown.bs.modal', function(e){
				$(this).data('lastEvent', e);
			})
			.on('hide.bs.modal', function(e){
				if (document.location.hash.search('qv_') == 1) {
					RZB2.ajax.setLocation(document.location.href.match(/^[^#]*/)[0]);
				}
			})
		.find('a.arrow').on('click', function(e){
			var forward = $(this).hasClass('next') ? 'next' : 'prev';
			var lastEvent = $modalQuickView.data('lastEvent');
			var $itemWrap = $(lastEvent.relatedTarget).closest('.catalog-item-wrap');
			var $itemWrapNext = $itemWrap[forward]('.catalog-item-wrap');
			if ($itemWrapNext.length < 1) {
				var backward  = $(this).hasClass('next') ? 'prev' : 'next';
				$itemWrapNext = $itemWrap[backward+'All']('.catalog-item-wrap').last();
			}
			if ($itemWrapNext.length < 1) {
				$itemWrapNext = $itemWrap;
			}
			$modalQuickView.switchTo($itemWrapNext);
			RZB2.ajax.setLocation('#qv_' + $itemWrapNext.attr('id'));
			e.preventDefault();
		});
		RZB2.ajax.quickViewCheck = function (e) {
			var bsModal = $modalQuickView.data('bs.modal');
			var isShown = (typeof bsModal == "object" && bsModal.isShown);
			if (document.location.hash.search('qv_') == 1) {
				var qvItemId = document.location.hash.replace('qv_', '');
				if (isShown) {
					$modalQuickView.switchTo(qvItemId);
				} else {
					(function repeatClick(){
						if ($(qvItemId).find('.quick-view-switch').trigger('click').length < 1) {
							setTimeout(repeatClick, 1000);
						}
					})();
				}
				return;
			}
			if (isShown) {
				$modalQuickView.modal('hide');
			}
		};
		window.addEventListener('popstate', RZB2.ajax.quickViewCheck, false);
		$('div[data-quick-view-enabled]').attr('data-quick-view-enabled', 'true');
		RZB2.ajax.quickViewCheck();
	}
	
	// BUY IN ONE CLICK
	$('.product-page, .catalog-page, .special-blocks, .search-results-page, .basket-big-page, .small-basket-buy-wrap, #modal_quick-view').on('click', '.one-click-buy', oneClickBuyHandler);
	$('.viewed-products').find('[data-toggle="modal"]').off('click').on('click', function(e){
		var $this = $(this);
		$($this.data('target')).modal('show', this);
		if ($this.hasClass('one-click-buy')) {
			oneClickBuyHandler.call(this, e);
		}
	});
	$('#modal_quick-buy').on('submit', 'form', function(e){
		e.preventDefault();
		RZB2.ajax.oneClick.Post($(this));
	});



	// SUBSCRIBE IN FOOTER
	if (typeof window.frameCacheVars !== "undefined" && window.isFrameDataReceived == false) {
		BX.addCustomEvent("onFrameDataReceived", function (json){
			b2.init.formValidation('.form_footer-subscribe, .subscribe-edit', true);
		});
	} else {
		b2.init.formValidation('.form_footer-subscribe, .subscribe-edit', true);
	}

	// RATING
	$(document).on('click', '.rating-stars i', function(e){
		var $div = $(this).closest('.rating-stars');
		if ($div.data('disabled') === true) return;

		var arParams = RZB2.ajax.Vote.arParams[$div.data('params')];
		RZB2.ajax.Vote.do_vote(this, arParams, e);
	});

	//Cancle print
	$(document).on('click','#print-header .cancel', function(e){
        e.stopPropagation();
	});
}

var doAfterLoadFormOneClick = function(content) {
	setTimeout(function(){
        b2.init.quantityChange && b2.init.quantityChange('#modal_quick-buy');
	},1000);
};

var oneClickBuyHandler = function (e) {
	e.preventDefault();
	var $this = $(this);
	if (typeof RZB2.ajax.oneClick === 'undefined') {
		RZB2.ajax.oneClick = new RZB2.ajax.FormUnified({
			ID: 'modal_quick-buy',
			AJAX_FILE: SITE_DIR + "ajax/one_click.php",
		});
	}
	RZB2.ajax.oneClick.Load([
		{name: "id", value: $this.data('id')},
		{name: "RZ_BASKET", value: $this.data('basket')},
		{name: 'PROPS', value: $this.data('props')}
	],doAfterLoadFormOneClick);
};

if (typeof domReady != "undefined" && domReady == true) {
	RZB2_initCommonHandlers(jQuery);
} else {
	jQuery(document).ready( RZB2_initCommonHandlers );
}

//# sourceURL=js/back-end/handlers/commons.js
