// .carousel inside target MUST have an ID. I don't check for its
// existence here
b2.init.feedbackCarousel = function(target){
	$(target).find('.carousel').each(function(){
		var $t = $(this),
			number = $t.find('.comment.item').length,
			active = $t.find('.comment.active').index(),
			dots = $(), id = '#' + $t.attr('id');

		$t.on('swipeleft', function() {
			$t.carousel('next');
		}).on('swiperight', function() {
			$t.carousel('prev');
		});

		$t.find('.item').eq(0).find('.text').heightControl().addClass('initialized');

		$t.on('slid.bs.carousel', function(e){
			if ($(e.relatedTarget).find('.text').hasClass('initialized')) return;
			$(e.relatedTarget).find('.text').addClass('initialized').heightControl();
		})

		for (var i = 0; i < number; i++){
			dots = dots.add($('<i class="dot" data-target="' + id + '" data-slide-to="' + i + '"></i>'))
		}
		dots.eq(active).addClass('active');
		$t.find('.carousel-indicators').empty().append(dots);
	});
}