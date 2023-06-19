function initHurry(target) {
	$(target).find('.hurry-carousel').each(function () {
		new UmSlider($(this), {
			responsive: [
				{bp: 0, groupBy: 6},
				{bp: 767, groupBy: 1},
				{bp: 991, groupBy: 2},
				{bp: 1199, groupBy: 3},
				{bp: 1599, groupBy: 4},
				{bp: 1919, groupBy: 5}
			],
			centeringSingle: true,
			onChange: function (prev, next) {
				var t = this;
				t.animating = true;
				var animClass = "superscale";
				var delayOut = 0;
				var delayIn = 50;
				var interval = 100;
				var duration = 500;
				t.content.css({
					'height': t.content.outerHeight(),
					'overflow': 'hidden'
				}).addClass('perspective');
				prev.each(function () {
					var _ = $(this);
					var offset = _.position();

					// without setTimout browser sets position absolute first and THEN
					// gets .position(), which results in { 0, 0 } coords for all items
					setTimeout(function () {
						_.css({
							position: 'absolute',
							top: offset.top,
							left: offset.left,
							overflow: 'hidden'
						})
					}, 0);

					setTimeout(function () {
						_.addClass(animClass + '-out');
						setTimeout(function () {
							_.removeClass(animClass + '-out active');
							_.css({
								position: '',
								top: '',
								left: '',
								overflow: ''
							});
						}, duration);
					}, delayOut);
					delayOut += interval;
				});

				var reset;
				var limit = next.length;
				next.each(function (i) {
					var _ = $(this);
					setTimeout(function () {
						_.addClass(animClass + '-in active');

						setTimeout(function () {
							_.removeClass(animClass + '-in');

							if (i === next.length - 1) {
								t.animating = false;
								t.content.css({
									height: '',
									overflow: ''
								}).removeClass('perspective');
							}

						}, duration);

					}, delayIn);
					delayIn += interval;
				});
			}/*onChange: function(prev, next){*/
		});
		/*new UmSlider($(this), { */
	})
}
function initTimers(target) {
	$(target).find('.timer').each(function () {
		var $t = $(this);
		var liftoff = new Date($t.data('until'));
		$t.countdown({until: liftoff});
	})
}

function initRatingStars(target) {
	$(target).find('.rating-stars').on({
		click: function (e) {
			var _ = $(this);
			var index = _.index() + 1;
			_.closest('.rating-stars').removeClass('r1 r2 r3 r4 r5').addClass('r' + index);
			return false;
		},
		mouseenter: function (e) {
			$(this).nextAll().removeClass('hovered');
			$(this).prevAll().addBack().addClass('hovered');
		},
		mouseleave: function (e) {
			$(this).siblings().addBack().removeClass('hovered');
		}
	}, 'i');
}
$(function () {
	initHurry(document);
	initRatingStars(document);
	initTimers(document);

	$('[data-tooltip]').tooltip({
		html: true
	});
});