function enableRating(ratingElement){
	var $r = $(ratingElement);
	$r.attr('data-disabled', false)
		.on({ /* BACK_END not need in project
			'click.rating': function(e){
				var $t = $(this),
					index = $t.data('index');
				$r.attr('data-rating', index);
				$(document).trigger('ratingchange', {
					itemid: $r.data('itemid'),
					rating: index,
					ratingElement: $r
				})
				//disableRating($r); // use to disable further rating interactions
				return false;
			}, */
			'mouseenter.rating': function(e){
				var $t = $(this),
					index = $t.data('index');
				$r.attr('data-hoverrating', index);
				$t.nextAll('i').removeClass('hovered');
				$t.prevAll('i').addBack().addClass('hovered');
			},
			'mouseleave.rating': function(e){
				var $t = $(this);
				$r.attr('data-hoverrating', '');
				$(this).siblings('i').addBack().removeClass('hovered');
			}
		}, 'i');

}
function disableRating(ratingElement){
	$(ratingElement).attr('data-disabled', true)
		.off('click.rating mouseenter.rating mouseleave.rating')
		.removeAttr('data-hoverrating')
		.children('i').removeClass('hovered');
}
b2.init.ratingStars = function(target){
	$(target).find('.rating-stars').each(function(){
		if ( !$(this).data('disabled') ){
			enableRating(this);
		}
	})
};