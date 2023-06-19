function initFull(){
	initCommons();

	if (typeof initTimers == 'function') {
        initTimers(document);
    }

	b2.curPage = $('main[data-page]').data('page');
	if (b2.curPage) b2.curPage = $.camelCase(b2.curPage);
	if (typeof b2.init[b2.curPage] === 'function') b2.init[b2.curPage]();

	function loadCommonChunks(){
		require([
			'um/picturefill-custom',
			'libs/baron.min',
			'init/initScrollbars',
			'libs/bootstrap/tooltip.min',
			'init/popups/initTooltips',
			'init/forms/initInputs',
			'util/formValidation',
			'init/forms/initFormValidation',
			'init/forms/initRatingStars',
//			'init/forms/initQuantityChange',     BACK_END moved to bx_catalog_item & basket templates
//			'init/toggles/initBtnActionToggles'  BACK_END not need in ready project
		], function(){
			window.picturefill();
            b2.init.selects(document);
			b2.init.scrollbars();
			b2.init.tooltips(document);
			b2.init.inputs(document);
			b2.init.formValidation('.modal-form, main.new-password-page');
            RZB2.utils.initValidForms && RZB2.utils.initValidForms();
			b2.init.ratingStars(document);
//			b2.init.quantityChange(document);  BACK_END not need in ready project
//			b2.init.btnActionToggles();        BACK_END not need in ready project
			$(window).trigger('b2ready');
		});
	}

	if (windowLoaded) loadCommonChunks();
	else $(window).load(loadCommonChunks);
}

var isFrameDataReceived = false;
if (typeof window.frameCacheVars !== "undefined")
{
	var spinners = [];
	BX.addCustomEvent("onFrameDataReceived", function (json){
		for (var i = 0; i < spinners.length; i++) {
			spinners[i].Stop();
			delete spinners[i];
		}
		spinners = [];
		$body = $(document.body);
		initFull();
		RZB2_initButtons();
		isFrameDataReceived = true;
	});
	jQuery( document ).ready(function($){
		$('.rz-loader').each(function(){
			var _ = $(this);
			var spinner = RZB2.ajax.spinner(_.parent());
			spinner.Start(_.data());
			spinners[spinners.length] = spinner;
		});
	});
} else {
	jQuery( document ).ready(initFull);
}

jQuery( document ).ready(RZB2_initButtons);

function RZB2_initButtons($){
	RZB2.ajax.BasketSmall.RefreshButtons();
	RZB2.ajax.Compare.RefreshButtons();
	RZB2.ajax.Favorite.RefreshButtons();
}