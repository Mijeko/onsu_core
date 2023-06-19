$(document).ready(function(){
	$(document).on('open', '.store-info.notification-popup', function(e){
		var _ = $(this);
		if (_.data('loaded') == true) return;

		var spinner = RZB2.ajax.spinner(_.find('.content'));
		spinner.Start({color: RZB2.themeColor});
		_.data('spinner', spinner);
	});
});
