b2.init.orderDetailsPage = function(){
	$('.bx-soa-section-content input[type="radio"]').each(function () {
        var $input = $(this).clone().hide(),
            $text = $(this).closest('label').text();
        $(this).closest('label').empty().append($input).append('<span class="radio-content"><span class="radio-fake"></span><span class="label-text">' + $text + '</span></span>');
    });


	$('input[type="checkbox"]').each(function () {
        var $input = $(this).clone().hide(),
            $text = $(this).closest('label').text();
        $(this).closest('label').empty().append($input).append('<span class="checkbox-content"><i class="flaticon-check14"></i>' + $text + '</span>');
    });

    $('select').each(function() {
        if ( !$(this).parent().hasClass('chosen-styled-select') && $(this).css('visibility') === 'visible' ) {
            var $select = $(this);
           b2.init.selects('#bx-soa-order');
            if (typeof b2.init.scrollbarsTargeted == "function") b2.init.scrollbarsTargeted('#bx-soa-order');
        }
    });
};

b2.init.orderHandlersDetailsPage = function(){
    $(document).off('click', 'li.active-result').on('click', 'li.active-result', function(){
        if (typeof BX != 'undefined') {
            var $this = $(this),
                $parent = $this.closest('.chosen-styled-select'),
                 nameSelect = $parent.find('select').find('option').eq($(this).val()-1).text();
            BX.fireEvent(BX.findChild(BX('bx-soa-order'), {"tag": 'select', "class": 'form-control', 'name': nameSelect}, true), 'change');
        }
    });

    $(document).on('click','.main-user-consent-request-popup-button-acc',function(){
        $('#bx-soa-orderSave .checkbox input[type="checkbox"]').prop('checked',true);
    });
    $(document).on('click','.main-user-consent-request-popup-button-rej',function(){
        $('#bx-soa-orderSave .checkbox input[type="checkbox"]').prop('checked',false);
    });
}