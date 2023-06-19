b2.init.tooltips = function(target){
	if (!$().tooltip) return;
	if (isHover) {
		// begin with specific ones
		$(target).find('.add-to-order').tooltip({
			// placement: 'auto top',
			html: true,
		})
		$(target).find('.out-of-stock .price-new').tooltip({
			title: 'Последняя цена'
		})
		// ...and finish with all which left
		$(target).find('[data-tooltip]').tooltip({
			'trigger': 'hover',
			// placement: 'auto',
			html: true,
		});
	}
}