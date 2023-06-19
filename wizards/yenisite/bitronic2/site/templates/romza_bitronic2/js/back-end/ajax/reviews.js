"use strict";
$(function ($) {
    var $body = $(document.body),
        strCatalogId = '#catalog_section';

    var getReviewsOnPage = function(){
       var $reviewsWrap = $('#backend-review-container'),
           data = {}, arIdsItems = [], spinner,inCatalog;

       if (!$reviewsWrap.length){
           setTimeout(getReviewsOnPage,300);
           return false;
       }

        arIdsItems = getItemsIdsInCatalog();
        inCatalog = $(strCatalogId).length ? 'Y' : 'N';
        data = {'get_block_reviews' : 'Y', 'items_of_reviews' : arIdsItems,'in_catalog_section' : inCatalog};
        spinner = RZB2.ajax.spinner($reviewsWrap);
        spinner.Start({color: RZB2.themeColor});

        return $.ajax({
            url: SITE_DIR + 'ajax/reviews.php',
            type: 'GET',
            data: data,
            success: function (msg) {
                spinner.Stop();
                $reviewsWrap.html(msg);
                RZB2.utils.initLazy($reviewsWrap)
            }
        });

    };

    var getItemsIdsInCatalog = function(){
        var arIds = [];
        $.each($(strCatalogId + ' [data-item-id]'), function(){
            arIds.push($(this).data('item-id'));
        });

        return arIds;
    };

    $(document).on('ready',function(){
        getReviewsOnPage();
    });

    $body.on('updateBlockReviews',function(){
        getReviewsOnPage();
    });
});