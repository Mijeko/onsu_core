(typeof(jQuery) != 'undefined')
&& jQuery(function ($) {
	var $container = $('#main-shop-reviews');
	var $s = RZB2.ajax.spinner($container);
	$s.Start();
	updateYRMS = function (page, path, count, call_url) {
		var data = [
			{'name': 'PAGE', 'value': page},
			{'name': 'URL', 'value': call_url},
		];
		return $.ajax({
			url: path + "/ajax.handler.php",
			type: "POST",
			dataType: "html",
			data: data,
			success: function (msg) {
				$container.html(msg);
				$s.Stop();
				b2.init.feedbackCarousel('.feedback');
			}
		});
	}
});
