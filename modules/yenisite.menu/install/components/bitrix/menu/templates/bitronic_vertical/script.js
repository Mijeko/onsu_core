$(document).ready(function(){
	/*$("#ye_mainmenu ul li ul li ul:not('.ye_submenu')").wrap("<div class='ye_submenu'></div>");
	$(".ye_submenu ul").each(function() {
		$(this).children("li:first").children(":first").addClass("ye_first");
	});
	$(".ye_first").append("<div class='ye_arrow'></div>");*/
	
	$('.active-link').children('ul').addClass('expanded');

	// Build HITS
	$('[data-ys-hit-key]').each(function() {
		var selector = '[data-ys-li-key=' + $(this).data('ys-hit-key') + ']',
			div = $(this).detach();

		if (!$(selector).find('.best').length) {
			$(selector).append(div);
		}
	});

	$('#ye_mainmenu ul li').hover(function() {
		var div = $(this).children('.best');

		if (div && $(this).data('ys-li-key') == div.data('ys-hit-key')) {
			div.show();
		}
	}, function() {
		var div = $(this).children('.best');

		if (div && $(this).data('ys-li-key') == div.data('ys-hit-key')) {
			div.hide();
		}
	});
	// End build HITS

	// Remove lines
	$('#ye_mainmenu > ul > li > a').hover(function() {
		$(this).parent().prev().attr('style', 'background: none !important;');
	}, function() {
		$(this).parent().prev().removeAttr('style');
	});
	// ---

	var li = $(this).find('.active-link:last'),
		cont = null;

	// li.each(function() {
	/*	var a = li.find('a').eq(0),
			span = null,
			li = null;

		if ( a.children().eq(0).is('span') == true && !a.hasClass('ye_first') ) {
			span = a.children().eq(0);
			cont = span.html();
			
			cont = '<span class="span-active">' + cont + '</span>';
			
		} else if ( a.children().eq(0).is('sup') == true || a.hasClass('ye_first') ) {
			cont = a.html();
			
			cont = '<span class="ye_first span-active">' + cont + '</span>';
			
		} else {
			cont = a.text();
			
			cont = '<span class="span-active">' + cont + '</span>';
		}
			
		li = a.parent();
		
		a.remove();
		li.prepend(cont);*/
	// });

	



	$('.ye-by-click ul li a').click(function(e) {

		//$('#mask').fadeIn('300');
		/*var needtoscroll = $(this).offset().top;
		console.log (needtoscroll );*/
		//scroll to top
		
		var ul = $(e.target).parent().next();
		
		if (ul.hasClass('expanded')) {
			ul.removeClass('expanded');
			
			ul.css("display", "none");
		} else {
			$('#ye_mainmenu ul.expanded').each(function() {
				if ($(e.target).is('span') == true && !$(e.target).parent().next().is('div.ye_submenu')) {
					$(this).removeClass('expanded');
					
					$(this).css("display", "none");
				}
			});
			
			ul.addClass('expanded');
			
			ul.css("display", "block");

			 //$('html,body').animate({scrollTop: $(this).offset().top-60},300); //MAKE SCROLL TO TOP
		}
	});

	$(".ye-by-click>ul>li>a").not("div.ye_submenu a").click(function(e) {
				var ul = $(e.target).parent().next();
		
				
			if ( $(e.target).parent().next().is("ul") == true ){
				//console.log(  "est' podmenu"+$(e.target).parent().css("background"));
				//$(this).css({"background":"Yellow"});
				//$(this).parent("li").css({"background":"Black"});
				if (ul.hasClass('expanded')) {
					$('html,body').animate({scrollTop: $(this).offset().top-60},300); //MAKE SCROLL TO TOP
				} 

			}	else {
						//$(this).css({"background":"red"});
		}
		
	});

	/*--------------------------
		MAKE OPACITY
	-----------------------------*/
/*$("body").on("mouseenter", "div", function(){
    $(this).data('timeout', setTimeout(function(){
       $("p").removeClass("hidden");
    }, 2000));
}).on("mouseleave", "div", function(){
    clearTimeout($(this).data('timeout'));
    $("p").addClass("hidden");
});*/
	
	
/*MAKE SHADOW WHEN OPACITY*/		
	$('#ye_mainmenu').on("mouseenter",".ye_submenu", function() {

		if ($(this).parents('#ye_mainmenu').prev('.ye_vert_mask').length) {

				$('div.ye_vert_mask').fadeIn('300');
		}
		});
		
	$('#ye_mainmenu>ul').mouseleave(function() {

		if ($('.ye_vert_mask').length) {

				$('div.ye_vert_mask').hide();
		}

	});


		$('#ye_mainmenu>ul>li>ul>li>.ye_submenu').mouseleave(function() {

				if ($('.ye_vert_mask').length) {

						$('div.ye_vert_mask').stop(true, true).hide();
				}

			});
			
		if ($('.ye_vert_mask').length) {

				$('div.ye_vert_mask').hover(function() {
						$('div.ye_vert_mask').hide();
					});
		}		
			
	

});