function initThumbs(target){
	var h = false, thumbs, timeout;
	if (Modernizr.mq('(max-width: 991px)')) h = true;

	function initSly(){
		var $t = $(this);
		if ( $t.hasClass('bigimg-thumbs') ){
			if ( $t.data('sly-inited') ) $t.sly('reload');
			else {
				var $prev = b2.el.$bigImgWrap.children('.prev');
				var $next = b2.el.$bigImgWrap.children('.next');
				$t.sly({},{
					load: function(){ $t.data('sly-inited', true);},
					active: function(e, index){
						var $thumbImg = $(this.items[index].el).find('img');
						var target = $thumbImg.attr('data-big-src');
						var targetDesc = $thumbImg.attr('data-img-desc');

						b2.el.$bigImgWrap.find('img').attr('src', target);
						$('.img-desc').html(targetDesc);
						
						$prev.add($next).removeClass('disabled');
						if ( index === 0 ) $prev.addClass('disabled');
						if ( index === $(this.items).length-1 ) $next.addClass('disabled');
					}
				}).sly('reload');
			}
		} else {
			var $next = $t.siblings('.next');
			var $prev = $t.siblings('.prev');
			var old = ($t.data('sly')) ? $t.data('sly').rel.activeItem : 0;
			if ( old === undefined ) old = 0;
			$t.sly('destroy').sly({
				horizontal: h, // Switch to horizontal mode.
				scrollBy: 1,
			    prev:     $prev, // Selector or DOM element for "previous item" button.
			    next:     $next, // Selector or DOM element for "next item" button.
			    startAt:  old,
			}).sly('on', 'active', function(e, index){
				var $targetImg = $(this.items[index].el).find('img');
				var medSrc = $targetImg.attr('data-med-src');
				var bigSrc = $targetImg.attr('data-big-src');
				
				// check if we are on product page
				if ( b2.el.$productPhotoImg && b2.el.$productPhotoImg.length ){
					b2.el.$productPhotoImg.attr('src', medSrc);
					if ( b2.s.photoViewType === "zoom" ){
						b2.el.$productPhotoImg.magnify({
							src: bigSrc || b2.el.$productPhotoImg.attr('data-big-src'),
						})
					}
				} else {
					var $img = $targetImg.closest('.thumbnails-wrap').siblings('.product-photo').find('img');
					$img.attr('src', medSrc);
				}
			}).sly('reload');
		}
	}
	function thumbsUpdate(){
		var visible = thumbs.filter(':visible');
		if ( visible.length === 0 ) return;

		if ( Modernizr.mq('(max-width: 991px)') ){
			if ( h ) visible.sly('reload');
			else {
				h = true;
				visible.each(initSly);
			}
		} else {
			if ( h ){
				h = false;
				visible.each(initSly);
			} else visible.sly('reload');
		}
	}
	$(window).on('resize', function(){
		clearTimeout(timeout);
		timeout = setTimeout(thumbsUpdate, 200);
	});

	thumbs = $(target).find('.thumbnails-frame.active, .thumbnails-wrap.active > .thumbnails-frame').each(initSly);
}