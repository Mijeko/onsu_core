$(document).ready(
        function() {
			$(".folder").click(
				function(e) {
					if($(e.target).hasClass("folder"))
					{
						 if($(e.target).hasClass("expanded"))
						 {
							$(e.target).children("ul").slideUp();
							$(e.target).removeClass('expanded');
							e.preventDefault();
							return false;
						 }
						 else
						 {
							$(e.target).children("ul").slideDown();
							$(e.target).addClass('expanded');
							e.preventDefault();
							return false;
						 }
					}
				}
			);	

			$(".folder").mouseover(function() {
				$(this).css('cursor', 'pointer');

			}).mouseout(function() {
				$(this).css('cursor', 'default');

    });	
		}		            
	);
	
	function CloseAllMenu() {
	 $(".folder ul").slideUp();
	 $(".folder").removeClass('expanded');
	}
	
	function OpenAllMenu() {
	$(".folder ul").slideDown();
	$(".folder").addClass('expanded');
	}
	
	