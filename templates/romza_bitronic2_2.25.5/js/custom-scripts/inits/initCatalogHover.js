function initCatalogHover(target){
	$(target)
	.find('.catalog').off('.catalogHover', '.catalog-item')
	.filter('.blocks, .list')
	.on('mouseenter.catalogHover', '.catalog-item', function(){
		if ( Modernizr.mq('(max-width: 767px)') ) return;
		var _ = $(this);
		if ( b2.s.hoverEffect === "detailed-expand" ){
			if ( _.hasClass('hovered') ){
				_.clearQueue();
			} else {
				_.delay(200).queue(function(){
					var fullV = _.find('.full-view');
					var oh = _.outerHeight();
					_.parent().css('height', oh);
					_.addClass('hovered');
					fullV.stop(true,true).slideDown(300, function(){
						_.closest('.combo-blocks').css('z-index', 3);
					});
					
					_.dequeue();
				})
			}
		} else {
			_.addClass('hovered');
		}
	}).on('mouseleave.catalogHover', '.catalog-item', function(){
		if ( Modernizr.mq('(max-width: 767px)') ) return;
		var _ = $(this);
		if ( b2.s.hoverEffect === "detailed-expand" ){
			if ( _.hasClass('hovered') ){
				_.delay(350).queue(function(){
					var fullV = _.find('.full-view');
					fullV.stop(true, true).hide();
					_.closest('.combo-target').css('z-index', '');
					_.removeClass('hovered');
					_.parent().css('height', '');
					
					_.dequeue();
				})
			} else {
				_.clearQueue().stop();
			}
		} else {
			_.removeClass('hovered');
		}
	})
}