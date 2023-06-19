b2.init.selects = function(target){
    var iOS = ( navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false );

    $(target).find('.chosen-container').remove();
    $(target).find('select').each(function() {
        var $this = $(this);

        if ($this.data('chosen')) $this.chosen('destroy');
        if (!$(this).parent().hasClass('chosen-styled-select')) {
            $(this).wrap('<div class="chosen-styled-select select-styled"></div>');
        }

        $this.on('chosen:ready', function (e, instance) {
            var $dd = $(instance.chosen.dropdown),
                $results = $dd.find('.chosen-results').addClass('baron__scroller');

            if (!$dd.find('.scroller__track').length) {
                $dd.append('<div class="scroller__track scroller__track_v"> \
				        <div class="scroller__bar scroller__bar_v"></div> \
					</div>');
            }
        }).chosen({
            width: 'auto',
            disable_search_threshold: 20,
            inherit_select_classes: true
        });
    });
}