function initBigSlider(){
	bs.slider = new UmSlider($('#big-slider'), {
		cycle: true,
		infinite: true,
		onChange: function(prev, next, dir, prevN, nextN){
			var animOutObjects = prev.children();
			var animInObjects = next.children();
			var prevText = animOutObjects.filter('.text');
			var prevMedia = animOutObjects.filter('.media');
			
			var aPrevText = (typeof bs.slides[prevN] != 'undefined' && typeof bs.slides[prevN].text != 'undefined') ? 
								(bs.slides[prevN].text.anim || bs.defaults.text.anim) : bs.defaults.text.anim;
			var aPrevMedia = (typeof bs.slides[prevN] != 'undefined' && typeof bs.slides[prevN].media != 'undefined') ?
								(bs.slides[prevN].media.anim || bs.defaults.media.anim) : bs.defaults.media.anim;
			var aNextText = (typeof bs.slides[nextN] != 'undefined' && typeof bs.slides[nextN].text != 'undefined') ?
								(bs.slides[nextN].text.anim || bs.defaults.text.anim) : bs.defaults.text.anim;
			var aNextMedia = (typeof bs.slides[nextN] != 'undefined' && typeof bs.slides[nextN].media != 'undefined') ? 
								(bs.slides[nextN].media.anim || bs.defaults.media.anim) : bs.defaults.media.anim;
			
			var nextMedia = animInObjects.filter('.media').css('display', 'none');
			var nextText = animInObjects.filter('.text').css('display', 'none');

			prevMedia.velocity('finish', true)
			.velocity('transition.' + aPrevMedia + 'Out', {
				duration: 500,
				complete: function(){
					if ( !prevText || prevText.length === 0){
						prev.removeClass('active').trigger('slid.out');
						next.addClass('active');
						bs.animIn(nextMedia, nextText, aNextMedia, aNextText, next);
					};
				},
			});
			prevText.velocity('finish', true)
			.velocity('transition.' + aPrevText + 'Out', {
				duration: 500,
				complete: function(){
					prev.removeClass('active').trigger('slid.out');
					next.addClass('active');
					bs.animIn(nextMedia, nextText, aNextMedia, aNextText, next);
				},
			});
		}
	});
}