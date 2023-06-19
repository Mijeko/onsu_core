function initModalGallery(){
	if (!b2.el.$bigImgWrap.length || b2.el.$bigImgModal.data('inited')) return;

	b2.el.$bigImgPrev = b2.el.$bigImgWrap.children('.prev');
	b2.el.$bigImgNext = b2.el.$bigImgWrap.children('.next');
	var bigImg = b2.el.$bigImgWrap.find('img');
	var bigimgDesc = b2.el.$bigImgWrap.children('.img-desc');
	
	b2.el.$bigImgWrap.on('click', function(e){
		var target = $(e.target);
		if ( !target.is(bigImg) && !target.is(bigimgDesc) ) b2.el.$bigImgModal.modal('hide');
	})
	b2.el.$bigImgPrev.length && b2.el.$bigImgPrev.on('click', function(){
		b2.el.$bigImgModal.find('.thumbnails-frame.active').sly('prev');
		return false;
	});
	b2.el.$bigImgNext.length && bigImg.on('click', function(){
		b2.el.$bigImgModal.find('.thumbnails-frame.active').sly('next');
		return false;
	});
	b2.el.$bigImgNext.length && b2.el.$bigImgNext.on('click', function(){
		b2.el.$bigImgModal.find('.thumbnails-frame.active').sly('next');
		return false;
	});

	b2.el.$bigImgModal.trigger('modal-gallery-inited').data('inited', true);


	$('body').on('keydown', function(e) {
		if (e.keyCode == 37) {
			b2.el.$bigImgModal.find('.thumbnails-frame.active').sly('prev');
		} else {
			if (e.keyCode == 39) {
				b2.el.$bigImgModal.find('.thumbnails-frame.active').sly('next');
			}
		}
	});
}