$(document).ready(
    function(){
		if(typeof $.fancybox != 'function') return;
        $('.resizer2fancy').fancybox({type:"image"});
		$('.r2buff').fancybox({type:"image"});
    }
);
