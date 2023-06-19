jQuery(function($){
	$('main.account-personal-waiting-goods div.tab-content button.btn-delete').on('click', function(e){
		e.preventDefault();

		var $td = $(this).closest('td');
		var spinner = RZB2.ajax.spinner($td);
		spinner.Start();

		$.ajax({
			url: SITE_DIR + 'ajax/personal_products.php',
			type: "POST",
			data: {id: $(this).data('id')},
			dataType: "json",
			success: function(data) {
				if (typeof data == "object") {
					RZB2.ajax.showMessage(data.message, data.status);
					if (data.status == 'success') {
						$td.closest('tr').fadeOut();
					}
				} else {
					RZB2.ajax.showMessage('Bad responce from server', 'fail');
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				RZB2.ajax.showMessage('AJAX error: ' + textStatus, 'fail');
			},
			complete: function() {
				spinner.Stop();
				spinner = undefined;
			}
		});
	});
});