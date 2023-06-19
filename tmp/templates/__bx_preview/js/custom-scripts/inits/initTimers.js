function initTimers(target){
	$(target).find('.timer').each(function(){
		var $t = $(this);
		var liftoff = new Date($t.data('until'));
		$t.countdown({until: liftoff});
	})
}
