"use strict";
$(function ($) {
    var $body = $(document.body),
        strCatalogId = '#backend-viewd-container';

    var getViewedOnPage = function(){
        var $viewedWrap = $(strCatalogId),
            data = {}, arIdsItems = [], spinner,inCatalog;

        if (!$viewedWrap.length){
            setTimeout(getViewedOnPage,300);
            return false;
        }

        data = {'get_block_viewed' : 'Y'};
        spinner = RZB2.ajax.spinner($viewedWrap);
        spinner.Start({color: RZB2.themeColor});

        return $.ajax({
            url: SITE_DIR + 'ajax/viewed.php',
            type: 'GET',
            data: data,
            success: function (msg) {
                spinner.Stop();
                $viewedWrap.html($(msg).html());
                RZB2.utils.initLazy($viewedWrap)
                $('<div></div>').html(msg);
                RZB2.ajax.BasketSmall.RefreshButtons();
                b2.init.scrollbarsTargeted && b2.init.scrollbarsTargeted($viewedWrap);
            }
        });

    };

    $(document).on('ready',function(){
        getViewedOnPage();
    });
});