(function($){
    "use strict";

    function initTabsYenisite () {
        $('body').find('.tabs-component-yenisite .collapsible-header').each(function () {
            var $t = $(this);

            $t.on('click', function () {
                $t.parent().toggleClass('collapsed').children('.collapse').collapse('toggle');
            });
        });
    }

    if (typeof window.frameCacheVars !== "undefined") {
        BX.addCustomEvent("onFrameDataReceived", function (json) {
            $(document).ready(initTabsYenisite);
        });
    } else {
        $(document).ready(initTabsYenisite);
    }

})(jQuery);