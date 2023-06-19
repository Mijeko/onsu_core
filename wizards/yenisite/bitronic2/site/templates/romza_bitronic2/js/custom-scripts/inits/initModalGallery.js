function initModalGallery(){
	if (!b2.el.$bigImgWrap.length || b2.el.$bigImgModal.data('inited')) return;

	b2.el.$bigImgPrev = b2.el.$bigImgWrap.children('.prev');
	b2.el.$bigImgNext = b2.el.$bigImgWrap.children('.next');
	var bigImg = b2.el.$bigImgWrap.find('img');
	var bigimgDesc = b2.el.$bigImgWrap.children('.img-desc');
	var $body = $(document.body);
	
	b2.el.$bigImgWrap.on('click', function(e){
		var target = $(e.target);
		if ( !target.is(bigImg) && !target.is(bigimgDesc) ) b2.el.$bigImgModal.modal('hide');
	})
	b2.el.$bigImgPrev.length && b2.el.$bigImgPrev.on('click', function(){
		var $this = $(this),
			$thumbActive = b2.el.$bigImgModal.find('.thumbnails-frame.active'),
        	$allThumbs = b2.el.$bigImgModal.find('.thumbnails-frame .thumb'),
        	curIndexActive = $allThumbs.index($thumbActive) - 1;
        $thumbActive.sly('prev');
        if ( b2.el.$bigImgNext.length){
            b2.el.$bigImgNext.removeClass('disabled');
        }
        $thumbActive.data('sly').rel.activeItem == 0 ? $this.addClass('disabled') : '';
		return false;
	});
	b2.el.$bigImgNext.length && b2.el.$bigImgPrev.length && $body.on('get_active_item_index', function(e,index){
        var $thumbActive = b2.el.$bigImgModal.find('.thumbnails-frame.active');

        if (typeof $thumbActive.data('sly') == 'undefined') return;

		var lastItem = $thumbActive.data('sly').rel.lastItem;
        index == lastItem ? b2.el.$bigImgNext.addClass('disabled') : b2.el.$bigImgNext.removeClass('disabled');
        index == 0 ? b2.el.$bigImgPrev.addClass('disabled') : b2.el.$bigImgPrev.removeClass('disabled');
	});
	b2.el.$bigImgNext.length && bigImg.on('click', function(){
        var $this = $(this),
            $thumbActive = b2.el.$bigImgModal.find('.thumbnails-frame.active');
        $thumbActive.sly('next');
        if (b2.el.$bigImgPrev.length){
            b2.el.$bigImgPrev.removeClass('disabled');
		}
        $thumbActive.data('sly').rel.activeItem == $thumbActive.data('sly').rel.lastItem ? b2.el.$bigImgNext.addClass('disabled') : '';
		return false;
	});
	b2.el.$bigImgNext.length && b2.el.$bigImgNext.on('click', function(){
        var $this = $(this),
            $thumbActive = b2.el.$bigImgModal.find('.thumbnails-frame.active');
        $thumbActive.sly('next');
        if (b2.el.$bigImgPrev.length){
            b2.el.$bigImgPrev.removeClass('disabled');
        }
        $thumbActive.data('sly').rel.activeItem == $thumbActive.data('sly').rel.lastItem ? $this.addClass('disabled') : '';
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