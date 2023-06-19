b2.init.certPage = function(){
	var $modal = $('#modal-big-cert');

	$modal.on('show.bs.modal', function(e){
		var $relTarget = $(e.relatedTarget);
		$modal.find('span.img-placeholder').before('<div class="title-h2"></div>');
		$modal.find('.title-h2, .h2, h2').html($relTarget.data('name'));
		$modal.find('.div.desc span.value').html($relTarget.find('span').html());
		$modal.find('img, span.img-placeholder')
			.replaceWith('<img src="' + $relTarget.data('bigimg') + '" alt="' + $relTarget.data('name') + '">');
	});
}