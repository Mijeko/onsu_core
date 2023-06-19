function initDragSections() {
	$(document).find('[data-drag-page]').each(function() {
		var $drag = $(this).find('.drag-section-list'),
			page = $(this).attr('data-drag-page'),
			section = $(this).attr('data-drag-section'),
			dragSettings;

		if (typeof section === 'undefined') {
			// drag sections on the page
			switch (page) {
				case 'home-page':
					dragSettings = b2.s.dragSettingsHome;
				break;
				case 'product-page':
					dragSettings = b2.s.dragSettingsProduct;
				break;
			}
		} else {
			// drag sections in the block
			switch (section) {
				case '.product-info-sections.full':
					dragSettings = b2.s.dragSettingsProductInfo;
				break;
			}
		}

		dragula([$drag.get(0)]).on('drag', function(el) {
			$(el).parent().addClass('onDrag');
		}).on('dragend', function(el) {
			var order = 1;

			$(el).parent().removeClass('onDrag');

			$drag.children().each(function(i) {
				var section = $(this).data('drag-section');

				// we store the order of changing of the elements
				dragSettings[section] = order;
				$('#order-' + section).val(order);

				order = order + 1;
			});

			MoveDragSection(page, dragSettings);
		});
	});
}