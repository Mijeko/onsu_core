$.fn.equals = function(compareTo) {
  if (!compareTo || this.length != compareTo.length) {
    return false;
  }
  for (var i = 0; i < this.length; ++i) {
    if (this[i] !== compareTo[i]) {
      return false;
    }
  }
  return true;
};
ys_ms_ajax_page = 1;
function ys_ms_JSInit(ajax_page, site_id, red_url, cache_time){
$(document).ready(function () {

	var __this__ = $('#ys-ms-slider');
		__this__.speed = 200
		__this__.ajax_page = ajax_page
		__this__.site_id = site_id
		__this__.red_url = red_url
		__this__.cache_time = cache_time
		__this__.item_width = 240;

	__this__.check_nav = function(th)
	{
		var button_left = th.find('.button7');
		var button_right = th.find('.button8');
		
		if(th.find('li.showed:first').prev().length!=1)
			button_left.addClass('disabled');
		else
			button_left.removeClass('disabled');
		
		if(th.find('li.showed:last').next().length!=1)
			button_right.addClass('disabled');
		else
			button_right.removeClass('disabled');
			
	}
		

	 var currentCount;
	 var currentShow;
	__this__.check_count = function(th)
	{
		var slw = th.parent().width();
		var count = Math.floor(slw / 240);
		var now_count = th.find('ul li.showed').length;
		if(now_count != count)
			if(now_count > count){
				var now = th.find('ul li.showed:last')
				for(var i=0;i<now_count-count;i++){
					now.removeClass('showed');
					now = now.prev()
				}
			}else{
				var now = th.find('ul li.showed:last')
				if(now.length!=1){
					th.find('ul li:lt(' + count + ')').addClass('showed');
				}else{
					for(var i=0;i<count-now_count;i++){
						now = now.next()
						now.addClass('showed').css('left','0').css('opacity', '1');
					}
					now_count = th.find('ul li.showed').length;
					if(now_count != count){
						now = th.find('ul li.showed:first')
						for(var i=0;i<count-now_count;i++){
							now = now.prev()
							now.addClass('showed').css('left','0').css('opacity', '1');
						}
					}
				}
			}
		__this__.check_nav(th)
		currentShow = now_count;
		
		//slider max range
		var sliderBar = th.find('.slider1');
		if (sliderBar.hasClass('ui-slider') && th.find('li.loader').length == 0) {
			var name = th.attr('id').replace('block_', '');
			sliderBar.slider('option', 'max', (__this__.li_total_count(th, name) - count) * __this__.item_width);

			if (sliderBar.slider('option', 'max') <= 0) {
				sliderBar.slider('disable');
			} else {
				sliderBar.slider('enable');
			}
		}
	}

	__this__.check_height = function(th)
	{
		var ul = th.find('ul');
		var sliderBar = th.find('.slider1');
		var sliderTop = sliderBar.position().top - ul.position().top - 40;
		if (sliderTop > parseInt(ul.css('min-height'))) {
			ul.css('min-height', sliderTop+'px');
		}
	}
	
	__this__.bind_li = function(th)
	{
		th.find('ul li').unbind('hover').hover(function () {
			$(this).find('.item-popup').show();
			$(this).css({'z-index': 50});
			$(this).find('.item-popup').addClass('item-hover');
		}, function () {
			var openedMenu = $(this).find('.selectBox-menuShowing')
			if (openedMenu.length != 1) {
				$(this).find('.item-popup').fadeOut();
				$(this).css({'z-index': 1});
				$(this).find('.item-popup').removeClass('item-hover');
			}
		});

		var minh = 0;
		th.find('ul.ulmitem li.with_sku').each(function(){
			if(minh == 0 || $(this).height() > minh) {
				minh = $(this).height();
			}
		});
		if(minh > 0)
			th.find('li').css('height', minh + 'px');
	}
	
	__this__.ajax_loader_params = {
		loaderSymbols: ['0', '1', '2', '3', '4', '5', '6', '7'],
		loaderRate: 30
	}
	
	__this__.ajax_loader = function(obj)
	{
		obj.addClass("loader");
		obj.WAIT_STATUS = true;
		obj.WAIT_PARAM = __this__.ajax_loader_params;
		obj.WAIT_INDEX = 0;
		obj.WAIT_FUNC = function(){
			if(!obj) return;
			if(obj.WAIT_STATUS)
			{
				obj.html(obj.WAIT_PARAM.loaderSymbols[obj.WAIT_INDEX]);
				obj.WAIT_INDEX = obj.WAIT_INDEX < obj.WAIT_PARAM.loaderSymbols.length - 1 ? obj.WAIT_INDEX + 1 : 0;
				setTimeout(obj.WAIT_FUNC, obj.WAIT_PARAM.loaderRate);
			}
			else
				obj.removeClass("loader");
		};
		obj.WAIT_FUNC();
	}

	__this__.ajax_loader_stop = function(obj)
	{
		obj.WAIT_STATUS = false;
	}	

	__this__.li_total_count = function(th, name)
	{
		var pp = th.parent().parent();
		if(pp.hasClass('blc_special'))
			return parseInt(pp.find('.tit .count').html());
		else
			return parseInt(__this__.find('.slider_cat #tab_'+name+' a.count').html());
		return 0;
	}
	
	__this__.ajax_li_check = function(th, name, now_page)
	{
		var count = __this__.li_total_count(th, name);
			
		if(count/10 > now_page)
				return true;

		return false;
	}
	
	__this__.ajax_li = function(th, orientation)
	{
		__this__.check_height(th);
		var button_left = th.find('.button7');
		var button_right = th.find('.button8');

		if(orientation == "right")
		{
			var elem = th.find('li:last').not(".loader");
		
			if(!th.find('li.showed:last').equals(elem)) 
				return;

			var now_page = parseInt(elem.find('input[name="iNumPage"]').val());
			var name = th.attr('id').replace('block_', '');
			
			if(!__this__.ajax_li_check(th, name, now_page)) 
				return;
				var new_page = now_page+1;
			var ajax_elem = elem
			.clone()
			.removeClass('showed')
			
			elem.after(ajax_elem)
			
			button_right.old_text = button_right.html()
			__this__.ajax_loader(button_right)
		}else if(orientation == "left"){
			var elem = th.find('li:first').not(".loader");
		
			if(!th.find('li.showed:first').equals(elem)) 
				return;

			var now_page = parseInt(elem.find('input[name="iNumPage"]').val());
			var name = th.attr('id').replace('block_', '');

			if(now_page <= 1) return;
				var new_page = now_page-1;
			var ajax_elem = elem
			.clone()
			.removeClass('showed')
			
			elem.before(ajax_elem)
			
			button_left.old_text = button_left.html()
			__this__.ajax_loader(button_left)	

		}else	return;

		__this__.ajax_loader(ajax_elem);
		th.find('.slider1').slider('disable');
		
		$.post(__this__.ajax_page, {
			'ys_ms_ajax_call':'y',
			'iNumPage':new_page,
			'tab_block':name,
			'red_url':__this__.red_url,
			'site_id':__this__.site_id,
			'cache_time':__this__.cache_time,
		}, function(data) {

			var appends = $(data).find('.ms_tab_block ul li')

			if(orientation == "right")
			{
				elem.after(appends);
				var new_li = th.find('li.showed:last').next();
				var prev_li = th.find('li input[name="iNumPage"][value="'+(new_page-1)+'"]')
					.first()
					.parent()
					.prevAll();
				prev_li.find('select').selectBox('destroy');
				prev_li.remove();
				__this__.ajax_loader_stop(button_right);
				button_right.html(button_right.old_text);
			}else if(orientation == "left"){
				elem.before(appends)
				var new_li = th.find('li.showed:first').prev();
				var next_li = th.find('li input[name="iNumPage"][value="'+(new_page+1)+'"]')
					.last()
					.parent()
					.nextAll();
				next_li.find('select').selectBox('destroy');
				next_li.remove();
				__this__.ajax_loader_stop(button_left);
				button_left.html(button_left.old_text);
			}else	return;

			__this__.ajax_loader_stop(ajax_elem)
			ajax_elem.remove();
			th.find('.slider1').slider('enable');
			
			__this__.check_nav(th)
			__this__.bind_li(th)
			__this__.add2basket_events(th)
			__this__.check_count(th);
			appends.find("select").selectBox();
			$('<div></div>').html(data); // for execute JS in data
		});	
		
		__this__.check_nav(th)							
													 

	}

	var isSliding = false;
	var isNormalized = true;

	__this__.ajax_slide = function(th, page)
	{
		var name = th.attr('id').replace('block_', '');
		__this__.check_height(th);

		$.post(__this__.ajax_page, {
			'ys_ms_ajax_call':'y',
			'iNumPage':page,
			'tab_block':name,
			'red_url':__this__.red_url,
			'site_id':__this__.site_id,
			'cache_time':__this__.cache_time,
		}, function(data) {

			var replacement = $(data).find('.ms_tab_block ul li');
			replacement.addClass('showed');
			//if (isSliding == true) {
				replacement.css('left', '-'+th.find('.slider1').slider('value')+'px');
			//}

			th.find('li[data-page="'+page+'"]').replaceWith(replacement);
						
			//__this__.check_nav(th)
			__this__.bind_li(th);
			__this__.add2basket_events(th);
			//__this__.check_count(th);
			replacement.find("select").selectBox();
			$('<div></div>').html(data); // for execute JS in data
			if (isSliding == false) {
				__this__.afterSlideStop(th);
			}
		});	
	}

	__this__.normalize = function(li)
	{
		li.css('opacity', '1').css('left','0');
		li.parent('ul').css('width', '100%');
	}

	__this__.afterSlideStop = function(th)
	{
		if (th.find('li.loader').length > 0) return;

		th.find('ul li').each(function(){
			if ($(this).position().left >= $(this).parent().position().left) {
				$(this).removeClass('showed');
				return false;
			}
		  }).toggleClass('showed').css('left','0').css('opacity', '1');

		__this__.check_count(th);
		__this__.check_nav(th);

		th.find('li.foobar').remove();

		var firstPage = parseInt(th.find('li.showed:first').find('input[name="iNumPage"]').val());
		var lastPage = parseInt(th.find('li.showed:last').find('input[name="iNumPage"]').val());

		var prev_li = th.find('li input[name="iNumPage"][value="'+firstPage+'"]')
			.first()
			.parent()
			.prevAll();
		prev_li.find('select').selectBox('destroy');
		prev_li.remove();

		prev_li = th.find('li input[name="iNumPage"][value="'+lastPage+'"]')
			.last()
			.parent()
			.nextAll();
		prev_li.find('select').selectBox('destroy');
		prev_li.remove();
		th.css('overflow', 'visible').find('ul').css('width', '100%');

		isNormalized = true;
	}


		//var	thisParent=	ms_tab_block;
		var Maxrange = 0;
		var lastSlideValue = 0;
    	var internalSlideCalling = false;
    	var value =0;
    	var startValue;
    	var stopValue;
			
    	//WE CREATE METHOD FOR SLIDER
		__this__.slidercreate = function(th)
		{
			th.find( ".slider1" ).each(function () {
				var ignoreSlide = false;
				var now_count = 1;
				$(this).slider({

 	        	animate: true,
              	//range: "min",
              	value: 0,
              	min: 0,
              	max: 0,

	        	step: 1,
              				
              	slide: function( event, ui ) {
              		if (ignoreSlide) {
              			ignoreSlide = false;
              			return false;
              		}
              		var all_li = th.find('li');

              		all_li.css('left', '-'+ui.value+'px');

              		var leftIndex = Math.floor(ui.value / __this__.item_width);
              		var leftLi = all_li.eq(leftIndex);

              		if (leftLi.hasClass('foobar')) {
              			var leftPage = leftLi.attr('data-page');
              			all_li.filter('.foobar[data-page="'+leftPage+'"]').removeClass('foobar').each(function(){
          					__this__.ajax_loader($(this));
              			});
              			__this__.ajax_slide(th, leftPage);
              		}

              		var rightIndex = leftIndex + now_count;
              		var rightLi = all_li.eq(rightIndex);
              		while (rightLi.length == 0) {
              			rightLi = all_li.eq(--rightIndex);
              		}

              		if (rightLi.hasClass('foobar')) {
              			var rightPage = rightLi.attr('data-page');
              			if (rightPage == leftPage)
              				return true;

              			all_li.filter('.foobar[data-page="'+rightPage+'"]').removeClass('foobar').each(function(){
          					__this__.ajax_loader($(this));
              			});
              			__this__.ajax_slide(th, rightPage);
              		}

              	},
          		

          		create: function(event, ui){
          		},
          		start: function(event, ui){
          			isSliding = true;

          			if ($(this).find('.ui-slider-handle').is(':hover'))
          				ignoreSlide = true;

          			if (isNormalized == false) return true;
          			isNormalized = false;

          			var ul = th.find('ul');
          			now_count = ul.find('li.showed').length;
          			var liLoader = $('<li class="foobar"></li>');
          			var countBefore = ul.find('li.showed:first').prevAll().length;
          			var totalCountBefore = Math.floor(ui.value / __this__.item_width);
          			var firstPage = ul.find('li:first input[name="iNumPage"]').val();

          			for (var i = countBefore; i < totalCountBefore; i++) {
          				var foobar = liLoader.clone();
          				foobar.attr('data-page', parseInt(firstPage) - Math.ceil((i + 1 - countBefore) / 10));
          				ul.prepend(foobar);
          			}

          			var countAll = ul.find('li').length;
          			var totalCount = now_count + Math.round($(this).slider('option', 'max') / __this__.item_width);
          			var lastPage = ul.find('li:last input[name="iNumPage"]').val();

          			for (var i = countAll; i < totalCount; i++) {
          				var foobar = liLoader.clone();
          				foobar.attr('data-page', parseInt(lastPage) + Math.ceil((i + 1 - countAll) / 10));
          				ul.append(foobar);
          			}

          			th.css('overflow', 'hidden')
          			  .find('ul').width($(this).slider('option', 'max')+$(document).width())
          			  .find('li').css('left', '-'+ui.value+'px')
          			  .not('.showed').addClass('showed');

					th.find('.button7').addClass('disabled');
					th.find('.button8').addClass('disabled');
					__this__.check_height(th);
          		},

          		stop: function (event, ui){
          			isSliding = false;

          			var sliderShift = ui.value % __this__.item_width;
          			if (sliderShift > 0) {
          				$(this).slider('value', ui.value + __this__.item_width - sliderShift);
          			}
          			__this__.afterSlideStop(th);
          		},

          		change: function(event, ui) { 
				}

			});
	
		});
	}
	//		
	__this__.bind_nav = function(th)
	{
		var button_left = th.find('.button7')
		var button_right = th.find('.button8')
			
		var minh = 0;
		    th.find('ul.ulmitem li.with_sku').each(function(){
		        if(minh == 0 || $(this).height() > minh) {
		        	minh = $(this).height();
		        }
		    });
			if(minh > 0)
			th.find('li').css('height', minh + 'px');

		var leftInterval;

		button_left.unbind("mousedown").on("mousedown", function(){
			scrollLeft();
			leftInterval = setInterval(scrollLeft, 300);
		});

		button_left.unbind("mouseup").on("mouseup", function(){
			clearInterval(leftInterval);
		});

		button_left.unbind("mouseout").on("mouseout", function(){
			clearInterval(leftInterval);
		});
		
		var scrollLeft = function(){
			if (th.find('li.loader').length > 0) return false;
			
			th.find('li').stop(true, true);
			var next_li = th.find('li.showed:first').prev();
			if(next_li.length!=1)
				return false;
			__this__.css('overflow', 'hidden');

			var last_li = th.find('li.showed:last');
			var left = $(last_li).width()+20;
			var all_li = th.find('li.showed:not(:last)');
			totalCountShow=th.find("li.showed").size();
			next_li.addClass('showed').css('opacity', '0').css('left','-'+left+'px');
			all_li.css('left','-'+left+'px');
			last_li.css('left','-'+left+'px');
			th.find('ul').css('width', '200%');
			last_li.animate(
				{
					'left': 0+'px',
					'opacity': '0'
				}, 
				__this__.speed,
				function(){
					__this__.normalize($(this)) 
					$(this).removeClass('showed');
					__this__.check_nav(th)
					__this__.check_count(th);
					__this__.css('overflow', 'visible');
				}
			);
			all_li.animate(
				{
					'left': 0+'px'
				}, 
				__this__.speed, 
				function(){	__this__.normalize($(this))}
			);
			next_li.animate(
				{
					'left': 0+'px',
					'opacity': '1'
				}, 
				__this__.speed,
				function(){	__this__.normalize($(this))}
			);
			__this__.ajax_li(th, "left");
          	
          	var uiSlider = th.find(".slider1");
          	uiSlider.slider("value", uiSlider.slider("value") - __this__.item_width);
			//PUSH_BACK
  			Prevli = th.find('li.showed:first').prevAll("li").size();
			if ( ( uiSlider.slider("value") == 0 ) && ( Prevli>0 ) ) {
				uiSlider.slider("value",  uiSlider.slider("value") + Prevli*__this__.item_width);
			}
			return true;
		}

		var rightInterval;

		button_right.unbind("mousedown").on("mousedown", function(){
			scrollRight();
			rightInterval = setInterval(scrollRight, 300);
		});

		button_right.unbind("mouseup").on("mouseup", function(){
			clearInterval(rightInterval);
		});

		button_right.unbind("mouseout").on("mouseout", function(){
			clearInterval(rightInterval);
		});

		var scrollRight = function(){
			
			th.find('li').stop(true, true);
			var next_li = th.find('li.showed:last').next();
			if(next_li.length!=1)
				return false;
			__this__.css('overflow', 'hidden');
			
			var all_li = th.find('li.showed:not(:first)');
			var first_li = th.find('li.showed:first');
			totalCountShow=th.find("li.showed").size();
			next_li.addClass('showed').css('opacity', '0');
			var left = $(first_li).width()+20;
			th.find('ul').css('width', '200%');
			first_li.animate(
				{
					'left': '-'+left+'px',
					'opacity': '0'
				}, 
				 __this__.speed,
				function(){
					__this__.normalize($(this));
					$(this).removeClass('showed');
					__this__.check_nav(th);
					__this__.check_count(th);
					__this__.css('overflow', 'visible');
				}
			);
			all_li.animate(
				{
					'left': '-'+left+'px'
				}, 
				 __this__.speed, 
				function(){	__this__.normalize($(this)) }
			);
			next_li.animate(
				{
					'left': '-'+left+'px',
					'opacity': '1'
				}, 
				 __this__.speed,
				function(){	__this__.normalize($(this)) }
			);
			__this__.ajax_li(th, "right");

			var uiSlider = th.find(".slider1");
			uiSlider.slider("option", "value",  uiSlider.slider("option", "value") + __this__.item_width);

    		var remain = th.find('li.showed:last').nextAll("li").size();
        	if ( ( uiSlider.slider("value") >= uiSlider.slider("option", "max")) && (remain > 0) ){
				uiSlider.slider("value",   uiSlider.slider("value") - remain*__this__.item_width );
        	}
        	return true;
		}

		if (typeof $.fn.mousewheel != "function") return;
									
		//Reaction on mousweel
		th.bind('mousewheel DOMMouseScroll wheel', function(e, delta) {

			if (isNormalized == false) return false;

	  	   	sliderValueOld =$(this).find(".slider1").slider("value");
	    	
	    	if (th.find('li.loader').length > 0) {
	        	e.preventDefault();
	        	return;
	    	}

		    if ( delta ) {

		        var step = __this__.item_width;
		            step *= delta > 0 ? -1 : 1;

		        var sliderValueNew = sliderValueOld + step;

		       	var Prevli;
	   
       			//when mouseWheel down
        		if (sliderValueNew>sliderValueOld){
					if (scrollRight() == false) return;
				}
				//When mousewheel down
				if (sliderValueNew<sliderValueOld){											
					if (scrollLeft() == false) return;
				} // if sliderValueNew<sliderValueOld
				
	        	e.preventDefault();
			}
		});

	}

	__this__.add2basket_events = function(th)
	{
		th.find(".add2basket").attr('onclick','ys_ms_ajax_add2basket(this);return false;');
	}
	
	__this__.init = function(th)
	{
		if(!th)
			return;
		th.find('ul').css('min-height', '0');
		__this__.slidercreate(th);		
		__this__.check_count(th);
		__this__.bind_nav(th);
		__this__.bind_li(th);
		__this__.add2basket_events(th);
		th.find("select").selectBox();
		$(window).resize(function(){
			__this__.check_count(th);
		});
	}
	

	__this__.ajax_block = function(name)
	{
		var loader_parent = __this__.find('.slider_cat li span#tab_'+name);
		var loader = loader_parent.find('.notloader');
		if(loader.hasClass('loader')) 
			return;
		
		var loader_big = __this__.find('.ms_tab_block#loader');
		loader_big.find('ul').html('');
		
		var count = 0;
		if(count = parseInt(loader_parent.find('.count').html())){
			if(count>10)
				count = 10;
			for ( i = 0; i < count; i++ )
				loader_big.find('ul').append($('<li></li>'));
		}

		__this__.check_count(loader_big);
		loader_big.find('li').each(function(){
			__this__.ajax_loader($(this));
		});
		loader_big.show();
		
		__this__.ajax_loader(loader);
		loader_parent.find('.tab_main').css('opacity', '0');
		loader_parent.find('.count').css('opacity', '0');

		$.post(__this__.ajax_page, {
			'ys_ms_ajax_call':'y',
			'tab_block':name,
			'red_url':__this__.red_url,
			'site_id':__this__.site_id,
			'cache_time':__this__.cache_time,
		}, function(data) {
		
			loader_big.find('li').each(function(){
				__this__.ajax_loader_stop($(this));
				$(this).remove();
			})
		
			loader_parent.find('.tab_main').css('opacity', '1')
			loader_parent.find('.count').css('opacity', '1')
			__this__.ajax_loader_stop(loader)
			
			
			__this__.find('.ms_tab_block').hide();
			var append = $(data).find('.ms_tab_block#block_'+name)
				append.appendTo(__this__.find('.sl_wrapper'));
			__this__.init(append)
			
			__this__.find('.slider_cat li')
					.removeClass('active')
					.find('span#tab_'+name)
					.parent()
					.addClass('active')

					$('<div></div>').html(data); // for execute JS in data
					//__this__.slidercreate(append);
		});	

			
	}
	
	__this__.find('.ys-mainspec-rounded').off("mousedown click mouseover mouseout mouseleave mouseenter").on("mousedown click", function(e){
			if (!e) var e = window.event;
			if (e.stopPropagation) e.stopPropagation();
			e.cancelBubble = true;
		}).hover(function(){
			$(this).parents("li").first().addClass('child_hover');
		}, function(){
			if (!$(this).is(':active'))
		 	$(this).parents("li").first().removeClass('child_hover');
		});
	
	__this__.find('.slider_cat li').unbind('click').click(function(){
		if(!$(this).hasClass('active'))
		{
			var val = $(this).find('span').attr('id');
			val = val.replace('tab_', '');
			var block = __this__.find('.ms_tab_block#block_'+val)
			__this__.find('.slider_cat li')
					.removeClass('active')
					.find('span#tab_'+val)
					.parent()
					.addClass('active')
			__this__.find('.ms_tab_block').hide();
			if(block.length>0){
				block.show();
			}
			else
				__this__.ajax_block(val)
		}
	}).off('mousedown').on('mousedown', function(){
		if ($(this).find('a.ys-mainspec-rounded').is(':hover')) return;
		$(this).addClass('active');
	}).off('mouseup mouseleave').on('mouseup mouseleave', function(){
		var tabName = $(this).children('span').attr('id').substring(4);
		var block = $('#block_'+tabName);
		if (block.length > 0) {
			if (block.css('display') != 'none') return;
		}
		$(this).removeClass('active');
	});
	
	
	__this__.find('.ms_tab_block#loader').hide();
	__this__.find('.ms_tab_block').each(function(){
		__this__.init($(this));
	});




});

}


/**
* Add params to add_url
*
* @param {Object} select object
*/
function ys_ms_onSelectChange(select) {
	var a = $(select).parent().parent().parent().parent().find('div a.button2'),
		href,
		params,
		i = 0,
		flag = 0,
		val = select.name + '=' + select.value;
		
	$(select).next().find('span.selectBox-label').css('color', 'black');
	
	if (a.attr('href') === 'javascript:void(0);') {
		a.attr('href', a.attr('rev'));
	}
	
	href = a.attr('href');

	href = decodeURI(href);
	params = href.split('&');

	for (; i < params.length; i += 1) {
		if (params[i].indexOf(select.name) != -1) {
			if (select.value == 0) {
				params.splice(i, 1);
			} else {
				params[i] = val;
				flag = 1;
			}
		}
	}
	
	if ( select.value != 0 && !flag ) {
		params.push(val);
	}

	href = params.join('&');
	a.attr('href', href);
	
	a.removeClass('button_in_basket');
	$(select).parent().parent().find('select').each(function(){
		if(this.value == 0 ){
			a.addClass('button_in_basket');
			return false;
		}
	});
	
}

function ys_ms_ajax_add2basket(self){
	if(!$(self).hasClass('button_in_basket')){
		var button = $(self)
		var id = button.attr('id').replace('ys-ms-','');
		var sku_id = $(self).attr('data-sku-id');
		var splitData = id.split('-');
		var iblock_id = splitData[0];
		var element_id = splitData[1];
		var href = button.attr('href');
		if($('.yen-bs-box').length > 0 &&  typeof SITE_TEMPLATE_PATH != "undefined" && typeof yenisite_bs_flyObjectTo != "undefined"){
			var action_add2b = $('#action_add2b').attr('value') ;
			var ob_post_params = JSON.parse('{"'+href.substr(href.indexOf('?')+1).split('&').join('","').split('=').join('":"')+'", "action":"ADD2BASKET", "id":"'+element_id+'", "iblock_id":"'+iblock_id+'","sessid":"'+BX.message.bitrix_sessid+'", "action_add2b":"'+action_add2b+'", "main_page":"Y"}');
			var url = SITE_TEMPLATE_PATH+'/ajax/add2basket.php';
			
			if(sku_id > 0)
				ob_post_params.id = sku_id;
			else
				ob_post_params.id = element_id;
			if ($('#ajax_iblock_id_sku_main').length && sku_id > 0)
			{
				ob_post_params['iblock_id_sku'] = $('#ajax_iblock_id_sku_main').attr('value');
			}
			ob_post_params['SITE_ID'] = SITE_ID;
		
			$.post(url, ob_post_params, function(data) {
				// button.addClass("button_in_basket")
				var pic_src = $('#product_photo_'+element_id).attr('src');
				if($('#action_add2b').attr('value') == 'popup_window')
				{
					var arData = data.split('<!-- add2basket -->');
					$('.yen-bs-box').html(arData[0]);
					$('#add_2b_popup').html(arData[1]);
					$('#add_2b_popup').fadeIn('300');
					$('#mask').fadeIn('300');
				}
				else
				{
					var what = '#ys-ms-'+id+'-photo';
					var to = '.yen-bs-box';
					yenisite_bs_flyObjectTo(what, to);
					$('.yen-bs-box').html(data);
				}
			});
		}else{
			throw "stop";
		}
		
	}
}
