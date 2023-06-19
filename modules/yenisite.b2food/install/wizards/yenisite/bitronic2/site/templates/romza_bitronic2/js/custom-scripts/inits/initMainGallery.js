function initMainGallery(target){
	$(target).find('.add-photo-label').off('click.mainGallery')
	.on('click.mainGallery', function(e){
		if ( !$('body').hasClass('authorized') ){
			alert('Для совершения этого действия необходимо авторизоваться');
			return false;
		}
	})

	if (b2.el.$productPhotoImg && b2.el.$productPhotoImg.length) b2.set.photoViewType(b2.s.photoViewType);
	$(target).find('.product-photo').off('swipeleft swiperight').on('swipeleft', function(e){
		e.stopPropagation();
		$(this).siblings('.thumbnails-wrap').children('.thumbnails-frame').sly('next');
	}).on('swiperight', function(e){
		e.stopPropagation();
		$(this).siblings('.thumbnails-wrap').children('.thumbnails-frame').sly('prev');
	})
}