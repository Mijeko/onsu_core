function initPagination(target){
	$(target).find('.pagination').each(function(){
		new UmPagination(this);
	})
}