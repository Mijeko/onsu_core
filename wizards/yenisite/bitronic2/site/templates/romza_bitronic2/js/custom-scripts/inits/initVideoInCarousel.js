function initVideos(target){
    $(target).find('.video').each(function(){
        var $t = $(this),
            $siblings = $t.siblings(),
            $carousel = $t.closest('.carousel'),
            $modal = $t.closest('.modal'),
            $video = $t.closest('.video-wrap-inner'),
            $item = $t.closest('.item'),
            data = $t.data();

        if (typeof $t.data('src') == 'undefined') return;

        var $iframe = $('<iframe src="https://www.youtube.com/embed/'
            + data.src
            + '?enablejsapi=1'
            + (data.parameters !== undefined && 0 < data.parameters.length ? '&' + data.parameters : '')
            + '"></iframe>')
            .addClass('video').insertBefore($t);
        $t.remove();

        var autoplay = ( data.autoplay !== undefined ) ? 1 : 0,
            muted = ( data.muted !== undefined ) ? 1 : 0;

        var settings = {
            showinfo: ( data.showinfo !== undefined ) ? data.showinfo : 0,
            controls: ( data.controls !== undefined ) ? data.controls : 0
        }

        if ($carousel.length){
            $siblings = $siblings.add($carousel.find('.thumbnails-wrap.active, .thumbnails-frame.active'));
        }

        var player = new YT.Player($iframe.get(0), {
            playerVars: settings,
            events: {
                'onReady': function(e){
                    if ( muted ) {
                        e.target.mute();
                    }

                    if ( autoplay ) {
                        // if video is on first slide
                        if ($item.hasClass('active')) {
                            e.target.playVideo();
                        }

                        // play or stop video on change slide
                        $carousel.on('slid.bs.carousel', function(){
                            if ( $item.hasClass('active') && e.target.getPlayerState() !== YT.PlayerState.PLAYING ) {
                                // play video on change active slide
                                e.target.playVideo();
                            } else if ( !$item.hasClass('active') && e.target.getPlayerState() === YT.PlayerState.PLAYING ) {
                                // pause video on change active slide
                                e.target.pauseVideo();
                            }
                        });
                    }

                    if ($carousel.length) {
                        $carousel.on('slid.bs.carousel', function() {
                            if ( !$item.hasClass('active') && e.target.getPlayerState() === YT.PlayerState.PLAYING ) {
                                // pause video on change active slide
                                e.target.pauseVideo();
                            }
                        });
                    }

                    if ($modal.length) {
                        $modal.on('hide.bs.modal', function() {
                            if ( e.target.getPlayerState() === YT.PlayerState.PLAYING ) {
                                // pause video on change active slide
                                e.target.pauseVideo();
                            }
                        });
                    }
                },
                'onStateChange': function(e){
                    if ( e.data === YT.PlayerState.PAUSED || e.data === YT.PlayerState.ENDED){
                        if ($video.data('controls-on-play') === 'hide')
                            $siblings.fadeIn('slow');
                        $item.length && $item.removeClass('video-playing');
                    } else if ( e.data === YT.PlayerState.PLAYING ){
                        if ($video.data('controls-on-play') === 'hide')
                            $siblings.fadeOut();
                        $item.length && $item.addClass('video-playing');
                    }
                }
            }
        });
    })
}

//=========== CHANGED FOR COMPOSITE MODE TO WORK ===========//
function onYouTubePlayerAPIReady() {
    if (typeof window.frameCacheVars !== "undefined") {
        if ( isFrameDataReceived ) {
            initVideos(document);
        } else {
            BX.addCustomEvent("onFrameDataReceived", function(json) {
                initVideos(document);
            });
        }
    } else {
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