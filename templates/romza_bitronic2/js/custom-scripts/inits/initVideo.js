function initVideos(target){
	$(target).find('.player-here').each(function(){
		var $t = $(this),
			data = $t.data();
		var $iframe = $('<iframe src="https://www.youtube.com/embed/'
			+ data.videoid
			+ '?enablejsapi=1'
			+ (data.parameters !== undefined && 0 < data.parameters.length ? '&' + data.parameters : '')
			+ '"></iframe>')
		.addClass('player-here').insertBefore($t);
		$t.remove();

		var frame = $iframe.closest('.media'), slide, controls;
		if ( frame && frame.length ){
			slide = frame.closest('.slide');
			controls = slide.closest('.container').children('.controls');
		}
		
		var autoplay = ( data.autoplay !== undefined ) ? 1 : 0;
		var muted = ( data.muted !== undefined ) ? 1 : 0;
		var settings = {
			showinfo: ( data.showinfo !== undefined ) ? 1 : 0,
			controls: ( data.controls !== undefined ) ? 1 : 0
		}

		var player = new YT.Player($iframe.get(0), {
			playerVars: settings,
			events: {
				'onReady': function(e){
					if ( muted ){
						e.target.mute();
					}
					if ( autoplay && bs.slider ){
						// if video is on first slide
						if (slide.hasClass('active')){
							e.target.playVideo();
						}
						slide.on('slid.in', function(){
							if ( e.target.getPlayerState() !== YT.PlayerState.PAUSED){
								e.target.playVideo();
							}
						});
					}
					slide.on('click', function(ev){
						if ( $(ev.target).is('button, a') ){
							if (e.target.getPlayerState() !== YT.PlayerState.PLAYING){
								e.target.pauseVideo();
							}
							return true;
						}
						if ( e.target.getPlayerState() !== YT.PlayerState.PLAYING ){
							e.target.playVideo();
						} else {
							e.target.pauseVideo();
						}
					}).on('slid.out', function(){
						if (e.target.getPlayerState() === YT.PlayerState.PLAYING || 
							e.target.getPlayerState() === YT.PlayerState.BUFFERING){
							e.target.pauseVideo();
						}
					})
				},
				'onStateChange': function(e){
					if ( !bs.slider ) return;
					if ( e.data === YT.PlayerState.PAUSED || e.data === YT.PlayerState.ENDED){
						bs.slider.videoPlaying = false;
						bs.slider.counter = 0;
						// frame.removeClass('video-playing');
						//controls.show();
					} else if ( e.data === YT.PlayerState.PLAYING ){
						bs.slider.videoPlaying = true;
						// frame.addClass('video-playing');
						//controls.hide();
					}
				}
			}
		});
	})
}

//=========== CHANGED FOR COMPOSITE MODE TO WORK ===========//
function onYouTubePlayerAPIReady() {
	if (typeof window.frameCacheVars !== "undefined") 
	{
		if( isFrameDataReceived ) {
			initVideos(document);
		} else {
			BX.addCustomEvent("onFrameDataReceived", function(json) {
				initVideos(document);
			});
		}
	}
	else
	{
		if ( domReady ) {
			initVideos(document);
		} else {
			$(function(){
				initVideos(document);
			});
		}
	}
}

var tag = document.createElement('script');
tag.src = "//www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);