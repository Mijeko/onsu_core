var searchAreaHandler = function ($) {
    var $body = $('body');
    // SEARCH AJAX
    $body.on('click', '#search button.btn-buy', function(e){
        e.preventDefault();
        var $_ = $(this);
        if ($_.hasClass('main-clicked') && $_.hasClass('forced')) {
            var url = $("#bxdinamic_bitronic2_basket_string").length <= 0 ? BASKET_URL : $("#bxdinamic_bitronic2_basket_string").attr("href");
            location.href = url.length > 0 ? url : location.href;
            return;
        }
        if ($_.hasClass('buy')) {
            var spinner = RZB2.ajax.spinner($_);
            spinner.Start(smallSpinnerParams);
            RZB2.ajax.CatalogSection.AddToBasketSimple($_.data('product-id'), $_.data('iblock-id'), 1, spinner);
        }
    })
        .on('change', '#search #search-area', function(e){
            var _ = $(this);
            var $form = _.closest('form');
            _.find('option').each(function(){
                var category = 'category_' + $(this).data('category');
                if ($(this).val() == _.val()) {
                    $form.addClass(category);
                    if (category == 'category_all'){
                        $form.find('.ajax-search-item').removeClass('hidden').addClass('show-category');
                        $form.find('.popup-ajax-footer').removeClass('hidden').addClass('show-category');
                    } else {
                        $form.find('tr.' + category).removeClass('hidden').addClass('show-category');
                        $form.find('div.' + category).removeClass('hidden').addClass('show-category');
                    }
                } else {
                    if(_.val() != "all"){
                        $form.find('tr.' + category).addClass('hidden');
                        $form.find('div.' + category).addClass('hidden');
                        $form.removeClass(category);
                    }
                }
            });
            setTimeout(function(){
                $('#popup_ajax-search').velocity("finish");
                $('#search-field').focus();
				$('#search-field').select(false);
            }, 50);
        })
        .on('click', '#search .search-example', function(e){
            e.stopPropagation();
            $('#search-field').focus();
        })
        .on('change blur', '#search #search-field', function(e){
            if ($(this).val().length < 1) {
                $('#search .search-example-wrap').removeClass('hidden');
            }
        })
        .on('focus', '#search #search-field', function(e){
            $('#search .search-example-wrap').addClass('hidden');
        });
};