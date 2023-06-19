b2.init.orderListPage = function(){
	var $form = $('.form_order-filter');
	var $clear = $form.find('.order-filter-reset');
	var $wrapper = $('#order-list');
	var spinner = RZB2.ajax.spinner($wrapper);
	var $statusButtons = $('button.order-filter-small');

	var refresh = function (data) {
		var trimData = [];
		$.each(data, function (index, obj) {
			if ('value' in obj) {
				if ($.trim(obj.value).length > 0) {
					trimData.push(obj);
				}
			}
		});
		data = trimData;
		if (data.length == 0) {
			$clear.attr('disabled', true);
			$clear.addClass('disabled');
		} else {
			$clear.removeAttr('disabled');
			$clear.removeClass('disabled');
		}
		spinner.Start({top: '30px'});
		$.ajax({
			url: $form.attr('action'),
			data: data,
			success: function (msg) {
				$wrapper.html(msg);
				spinner.Stop();
			}
		});

	};
	if ($form.length > 0) {
		$form.on('submit', function (e) {
			e.preventDefault();
			var data = $(this).serializeArray();
			var statusID = $statusButtons.filter('.active').data('status-id');
			if (statusID) {
				data.push({name: 'filter_status', value: statusID});
			}
			refresh(data);
			return false;
		});
		$form.on('change', function (e) {
			if ($(e.target).is('[type=text]')) {
				return false;
			}
			$form.submit();
		});
		var timer;
		$form.on('keyup', 'input[type=text]', function (e) {
			if (e.keyCode == 13) {
				return false;
			}
			if (timer) {
				clearTimeout(timer);
			}
			timer = setTimeout(function () {
				$form.submit();
			}, 500);
		});
	}
	var $document = $(document);
	$document.on('click', '.order-filter-reset', function (e) {
		e.preventDefault();
		$form[0].reset();
		$statusButtons.each(function(){
			var $this = $(this);
			if (!$this.data('status-id')) {
				$this.addClass('active');
			} else {
				$this.removeClass('active');
			}
		});
		refresh([]);
	});

	$statusButtons.on('click', function () {
		var $this = $(this);
		if ($this.hasClass('active')) {
			return false;
		}
		$this.parent().find('button.order-filter-small').removeClass('active');
		$this.addClass('active');
		var data = $form.serializeArray();
		if ($this.data('status-id')) {
			data.push({name: 'filter_status', value: $this.data('status-id')});
		}
		refresh(data);
	});

	// This code is from /js/custom-scripts/inits/pages/initAccountPage.js
	$('.switch-order-content').click(function(){
		var tr = $(this).closest('tr');
		var content = tr.children('.order-content, .order-payment-n-delivery-types');
		if ( tr.hasClass('shown') ){
			content.velocity('slideUp', 'fast', function(){
				tr.removeClass('shown');
			});
		} else {
			content.velocity('slideDown', {
				duration: 'fast',
				display: 'block',
				complete: function(){
					tr.addClass('shown');
				}
			})
		}
		tr.find('').slideToggle('fast', function(){
			tr.toggleClass('shown');
		});
	});
	// END /js/custom-scripts/inits/pages/initAccountPage.js
}

//# sourceURL=js/back-end/order_filter.js
