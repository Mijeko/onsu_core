function initPhotoThumbs(target){
	$(target).find('.photo-thumbs:visible').each(function(){
		var _ = $(this);
		var dots = _.find('.carousel-dots');
		var img = _.siblings('.photo').children('a').children('img');
		_.sly({
		    activateOn:     'mouseenter',  // Activate an item on this event. Can be: 'click', 'mouseenter', ...

		    pagesBar:       dots, // Selector or DOM element for pages bar container.
		    activatePageOn: 'click', // Event used to activate page. Can be: click, mouseenter, ...
		    pageBuilder:          // Page item generator.
		        function (index) {
		            return '<i class="carousel-dot"></i>';
		        },
		}).sly('on', 'active', function(e,i){
			var src = $(this.items[i].el).children('img').attr('data-medium-image');
			img.attr('src', src);
		}).sly('reload');
	});
}