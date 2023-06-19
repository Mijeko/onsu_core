$(document).ready(function(){
	 $('input[name = PERSONAL_PHOTO]').change(function(){
		$(this).siblings(".clearfix").find('.chosen-file').text(this.files[0].name);
	 });
});