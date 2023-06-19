/*---------------------------------------------------
Number of links in a section
param - ul or li
---------------------------------------------------*/
function num_in_razd(a) {
	return a.find('li').size(); // + 1
}

/*---------------------------------------------------
If li is section
param - li
---------------------------------------------------*/
function ifRazd(li) {
	if (li.children('ul').length != 0) {
		return true;
	}

	return false;
}

/*----------------------------------------------------------
Build the map of <ul>
param 	- ul
result	- array: [0, 1, 2, 1, ...]
	If 0 - first level of ul, 1 - second level of ul, etc.
----------------------------------------------------------*/
function mapOfUl(ul) {
	var map = [];
	var k = 0;
	ul.find('li').each(function() {
		k = $(this).parentsUntil('.subnav').length;
		switch(k) {
			case 1:
				map.push(0);
			break;
			
			case 3:
				map.push(1);
			break;
			
			case 5:
				map.push(2);
			break;
			
			case 7:
				map.push(3);
			break;
			
			case 9:
				map.push(4);
			break;
			
			case 11:
				map.push(5);
			break;
		}
	});
	return map;
}

/*-------------------------------------------------------------------------------
How many elements "num" are consecutive beggining with number "k" in array "map"
-------------------------------------------------------------------------------*/
function dop(map, k, num) {
	var kol = 0;
	for(var i = k; i < map.length; i++) {
		if (map[i] == num) {
			kol++;
		} else {
			break;
		}
	}
	
	return kol;
}

/*---------------------------------------------------
For example:
input: 	[1, 1, 2, 3, 3]
output:	[0, 1, 2, 2, 3, 4]
---------------------------------------------------*/
function indices(map) {
	var k = 0;
	var arr = [];
	
	for(var i = 0; i < map.length; i++) {
		if (map[i] != 0) {
			arr.push(i);
			k = dop(map, i, map[i]);
			arr.push(i+k-1);
			i += k-1;
			continue;
		}
	}
	
	return arr;
}

/*---------------------------------------------------
	Calculate number of columns, hS_new
	
	flag: 	1 - if .subnav contains section
			0 - if not contains
---------------------------------------------------*/
function calculate(subnav, flag, sIndex) {
	if (flag) { // IF INCLUDE SUBSECTION IN SUBMENU
		num_columns_old = num_columns[sIndex];
		num_columns[sIndex] = Math.ceil(h / hX);
		
		hS_new = hX;
		
		if (num_columns[sIndex] > 1) upd[sIndex] = 1;

		if ( subnav.offset().left < 0 || num_columns[sIndex] > colX || ( $(window).width() - liOffset - subnav.width() ) < -10 ) {
			num_columns_old = num_columns[sIndex];
			num_columns[sIndex] = Math.ceil(h / hY);
			hS_new = hY;
			
			if ( subnav.offset().left < 0 || num_columns[sIndex] > colY || ( $(window).width() - liOffset - subnav.width() ) < -10 ) {
				num_columns_old = num_columns[sIndex];
				num_columns[sIndex] = Math.ceil(h / hZ);
				hS_new = hZ;
			}
		}

		if (num_columns_old != num_columns[sIndex]) {
			upd[sIndex] = 1;
		}
		
	} else {   //without SUBSECTION IN SUBMENU
		
		num_li_in_column = Math.floor(hX / h_li);
		num_columns_old = num_columns[sIndex];
		num_columns[sIndex] = Math.ceil(kol / num_li_in_column);
		
		if (num_columns[sIndex] > 1) upd[sIndex] = 1;

		if ( subnav.offset().left < 0 || num_columns[sIndex] > colX || ( $(window).width() - liOffset - subnav.width() ) < -10 ) {
			num_columns_old = num_columns[sIndex];
			num_columns[sIndex] = Math.ceil(h / hY);
			num_li_in_column = Math.floor(hY / h_li);
		
			if (num_li_in_column * num_columns[sIndex] < kol) {
				num_columns_old = num_columns[sIndex];
				num_columns[sIndex] = Math.ceil(kol / num_li_in_column);
			}

			if (subnav.offset().left < 0 || num_columns[sIndex] > colY || ( $(window).width() - liOffset - $(this).width() ) < -10 ) {
				num_columns_old = num_columns[sIndex];
				num_columns[sIndex] = Math.ceil(h / hZ);
				num_li_in_column = Math.floor(hZ / h_li);

				if (num_li_in_column * num_columns[sIndex] < kol) {
					num_columns_old = num_columns[sIndex];
					num_columns[sIndex] = Math.ceil(kol / num_li_in_column);
				}
			}
		}
		
		if (num_columns_old != num_columns[sIndex]) {
			upd[sIndex] = 1;
		}
		
		if (num_columns[sIndex] == 1) {
			upd[sIndex] = 0;
		}
	} 
	
		var	maxCOLUMN = Math.floor($(window).width() / 310);
		if (delimsya){
			
		//if  (num_columns[sIndex]>3) {
					if (num_columns[sIndex]>maxCOLUMN){
						num_columns[sIndex] =  maxCOLUMN;
						hS_new= Math.ceil(h/num_columns[sIndex]);
						upd[sIndex] = 1;

							if  (num_columns[sIndex]>3) {
							num_columns[sIndex]=3;
							hS_new= Math.ceil( h/(num_columns[sIndex]) );
						 	upd[sIndex] = 1;
						 }


					} else  {
							//
						}

				}
		
		/*	num_columns[sIndex]=4;
			hS_new= hS_new= Math.ceil(h/num_columns[sIndex]);
		 	hX=hS_new;
		 	hZ=hS_new;
		 	hY=hS_new;*/
		
		
		
	//hS_new=(h/num_columns[sIndex]);
	//}
	//}
}

/*---------------------------------------------------
	Build .subnav
	
	flag: 	1 - if .subnav contains section
			0 - if not contains
	
	sIndex:	current .subnav index
---------------------------------------------------*/
function build(subnav, flag, sIndex) {
	

	if (upd[sIndex] == 1 && ifResize) {
		subnav.children('ul').remove();
		subnav.html(snav[sIndex]);
		subnav.children('li').wrapAll('<ul></ul>');
	} 
			
	if (h > hX && upd[sIndex]) {
		
			
			
		if (subnav.children('ul').length == 1) {

			for(var i = 0; i < num_columns[sIndex]-1; i++) {
				subnav.children('ul:last').after("<ul></ul>");

			}
			subnav.find('ul.ul_last').removeClass();
			subnav.find('ul:last').addClass('ul_last');
			subnav.find('ul:first').attr('style', '');

			if (flag) { //// IF INCLUDE SUBSECTION IN SUBMENU
				var nums = [];
				var k = 0;
				var number = 0;
				var perenos = [];
				var ost = 0;	// How many remain
				var flagP = 0;	// Overflow of column
				var fl = 0;
				var kolLiInRazd = 0; // How many <li> in section in one <ul>
		
				for(var i = 0; i < num_columns[sIndex]-1; i++) {
					var hh = 0;
					var per = 0;
					while(hh < hS_new) {
						
						
						var n = 0;
						if (ost > 0 && !flagP) {
							hh += h_li_in_razd * ost;
							k += ost;
							ost = 0;
						}

						if (flagP) {
							k += kolLiInRazd;
							per = ost - kolLiInRazd;
							ost -= kolLiInRazd;
							flagP = 0;
							number++;
							break;
						}
						var li_i = subnav.find('ul:first').children('li').eq(number); // go from menu items

						if ( ifRazd(li_i) ) {
							hh += h_li;
							n = Math.ceil( (hS_new - hh) / h_li_in_razd ); // How much more fit

							per = num_in_razd(li_i) - n;

							ost = per;

							if( n > num_in_razd(li_i) ) {
								n = num_in_razd(li_i);

								hh += n*h_li_in_razd;
								number++;
								k += n+1;

								continue;
							}

							if ( per*h_li_in_razd > hS_new ) {
								flagP = 1;
								kolLiInRazd = Math.ceil( hS_new / h_li_in_razd );
								per = kolLiInRazd;
								hh += n*h_li_in_razd;
								k += (n+1);
								fl = 1;

								break;
							}

							hh += n*h_li_in_razd;
							k += n+1;
							number++;

							if ( hh < hS_new ) {
								continue;
							}

							break;
						} else {
							hh += h_li;
						}
						
						number++;
						k++;

					} // while(hh < hS_new)
					nums.push(k);

					if (per < 0) {
						perenos.push(0);
					} else {
						perenos.push(per);
					}
				} // for(var i = 0; i < num_columns-1; i++)

				var map = mapOfUl( subnav.find('ul:first') );

				var dop_li;
				for(var i = num_columns[sIndex]-1; i > 0 ; i--) {
					dop_li = subnav.find('ul:first').find('li').slice(nums[i-1]);
					subnav.children('ul').eq(i).html(dop_li);
				}
		
				var ck = 0;
				var kl = 0;
				var mapUl = [];
				for(var i = 0; i < num_columns[sIndex]; i++) {
					kl = subnav.children('ul').eq(i).find('li').length;
		
					if ( i == 0 ) {
						mapUl[i] = map.slice(0, kl);
					} else {
						mapUl[i] = map.slice(ck, ck+kl);
					}
					ck += kl;
				}
		
				var ind = [];
				var sum; // Total number of add <ul> in one column
				for(var i = 0; i < num_columns[sIndex]; i++) {
					ind  = indices(mapUl[i]);
					sum = 0;
					var len = ind.length / 2;
					for(var j = 0; j < len; j++) {
						for(var k = 0; k < mapUl[i][ind[j*2]]; k++) {
							if (i) {
								subnav.children('ul').eq(i).find('li').slice(
									ind[j*2] + sum,
									ind[j*2+1] + sum + 1).wrapAll('<li><ul></ul></li>');
								sum++;
							}
						}
					}
				}

				if (subnav.find('ul:last').children('li').length == 0) {
					subnav.children('ul').slice(-2).addClass('ul_last');
					subnav.find('ul:last').slice(-1).remove();
				}

				upd[sIndex] = 0;
			} // flag 
			else { //if WITHOUT SUBSECTION IN SUBMENU
				var dop_li;
				for(var i = num_columns[sIndex]-1, k = 0; i > 0 ; i--, k++) {
					dop_li = subnav.find('ul:first').children('li').slice(num_li_in_column*i);
					subnav.children('ul').eq(i).html(dop_li);
					
				}
				
				upd[sIndex] = 0;
			}
		} // subnav.children('ul').length == 1
	} // h > hX && upd
	
	ifResize = 0;
}

//--------------------------------------------------------------------------
var num_columns = [];			// number of columns
var hX = 400;
var hY = 550;
var hZ = 850;
var colX = 3;
var colY = 4;
//var colZ = 5;

var hS_new = 0;

var upd = []; 		// whether to rebuild
var ifResize  = 0;	// Happend resize window is true
var ifCount  = 1;	// Whether to recalculate parameters of menu

var kol;				// total number of <li> in .subnav
var h;					// height of .subnav
var h_li_in_razd = 18;		// height of <li> in section
var h_li = 26;				// height of <li> without section
var num_li_in_column;	// total number of <li> in column
var num_columns_old;	// previous value of num_columns
var num_li_razd = 0;	// total number of <li> in section

var subnav_h = [];		// heigth of .subnav
var snav = [];			// <ul> in .subnav

var liOffset = 0;
var Fixleftslide = false; //Need drop parent li in main menu to the left if .subnav drop to the  left side
var maxCount = 0;
var divmegamenu;
var delimsya = 0;

var OldBrowsver;
//var divs = [];
//--------------------------------------------------------------------------

$(document).ready(function() {
	if ( $("html").hasClass("bx-ie8") ){
		OldBrowsver = true;
}

	var navigation = $('#navigator'); // this variable needed for cache and stop parsing too much
	var navLI = $("#navigator>li"); // this variable needed for cache and stop parsing too much
	var resizeTimer;
	var screenwidth = $(window).width();


/*RESPONSIVE HEIGHT FOR MENU
----------------------------------------------------------------------*/

//(function($){
					

	


	function fixButtonHeights() {

		//var ulNavigation = $('#navigator>li');
		var heights = new Array();
       
		// Loop to get all element heights
		navLI.each(function() {	
			// Need to let sizes be whatever they want so no overflow on resize
			$(this).css({"min-height":"0","max-height":"none", "height":"auto"});
	
			// Then add size (no units) to array
	 		heights.push($(this).height());
		});

		// Find max height of all elements
		var max = Math.max.apply( Math, heights );

		// Set all heights to max height
		navLI.each(function() {
			$(this).css('height', max + 'px');
            // Note: IF box-sizing is border-box, would need to manually add border and padding to height (or tallest element will overflow by amount of vertical border + vertical padding)
		});	
	}
	//$(document).ready(function() {
	//$(window).load(function() {
		// Fix heights on page load
	
		fixButtonHeights();
		// Fix heights on window resize
		$(window).resize(function() {
			// Needs to be a timeout function so it doesn't fire every ms of resize
			 clearTimeout(resizeTimer);
			 resizeTimer = 	setTimeout(fixButtonHeights, 200);
		});

	//});
//})(jQuery);



//----------------------------------------------------------------------


/*NAV HOVER
    ----------------------------------------------------------------------*/
 

		navigation.children('li').each(function() {
/* 			if ($(this).prev().hasClass('megamenu')) {
				divs.push($(this).prev().remove());
			} else {
				divs.push(0);
			} */

			$(this).addClass('hover');
			subnav_h.push( $(this).children('div.subnav').height() );
			$(this).removeClass('hover');
		});
		
		navigation.children('li').each(function() {
			snav.push( $(this).children('div.subnav').children('ul:first').html() );
		});
		
		navigation.find('li').hover(function() {
			navLI.find("a").not('.overflowstyle').stop( true , true ).removeAttr('style');
			navigation.find('.menubacklight a').not('.overflowstyle').stop( true , true ).removeAttr('style');
			$(this).find('div.subnav').stop( true , true );
						

			clearTimeout($.data(this,'timer'));
			$(this).siblings('.hover').removeClass('overflowstyle').removeClass('hover').removeClass('menubacklight').prev('li').removeClass('clean');
	     	 $(this).addClass('hover').addClass('menubacklight').prev('li').addClass('clean');
	        
	        var li = $(this);
		
			$(this).find('div.subnav').each(function() {
				var subnavIndex = $(this).parent().prevUntil('#navigator').length; // current subnav
				 	
				liOffset = navigation.children('li').eq(subnavIndex).offset().left; // offset of parent <li>
			
				$(this).children('ul:last').addClass('ul_last');
			
				kol = $(this).children('ul').children('li').length;
				h   = subnav_h[subnavIndex];

				
				
			
				var f = 0;	// flag if there is section
				
				$(this).children('ul').children('li').each(function() {
					if ( num_in_razd( $(this) ) > 0) {
						f = 1;
						//h_li_in_razd = $(this).find('li:first').height();
						num_li_razd += num_in_razd( $(this) );
					}
				});

				var tmp = $(this).find('li');
				for(var i = 0; i < tmp.length; i++) {
					if ( tmp.eq(i).parentsUntil('div.subnav').length == 1 && !ifRazd(tmp.eq(i)) ) {
						
						break;
					}
				}
				
			
				
				if ($(this).children('div.megamenu').length != 0) {
							
					var heightSubnav = $(this).outerHeight()+250;
					
					var screenBottom = $(window).scrollTop() +$(window).height();

					var suboffset =  $(this).offset().top;
					
		            	    var	widthofsubnav =	$(this).width();
		            	

						//check if submenu height with offetTop more than windowheight
						if (suboffset +heightSubnav > screenBottom)   {  
							
							if (f) {
									
								hX = 250;
								hY = 350;
								hZ = 450;
								delimsya =1;
							}
							else {
								hX = (screenBottom-suboffset-250);
								hY = (screenBottom-suboffset-250);
								hZ = (screenBottom-suboffset-250);
							}
								if (	hX<100 || hY<100 || hZ<100) {
								hX = 300;
								hY = 400;
								hZ = 750;
							 	}
									
							
								
						}
					

				} 
				else{
						hX = 400;
						hY = 550;
						hZ = 850;
				}






				calculate( $(this), f, subnavIndex );
				build( $(this), f, subnavIndex );
				
			
				// Add hits to subnav


				var wdt = 0; 	// width of .subnav
				var cnt1 = 0; 	// num of columns
				$(this).find('ul').each(function() {
                    if($(this).parent().attr('class') == 'subnav') {
						wdt = parseInt(wdt) + parseInt($(this).width() ) + 41;
					
						cnt1++;

                    }
				});
				if(cnt1 == 1) {					
					//$(this).find('ul:first-child').css('border', 'none');
				}

				

				 var meagamenuInc;

				if ($(this).children('div.megamenu').length != 0) {
					
					$(this).children('div.megamenu').children('div.item').slice(num_columns[subnavIndex]).each(function() {
						

						$(this).remove();
					

					});

						var num_col = (num_columns[subnavIndex]);
					meagamenuInc=true;
				} else meagamenuInc = false;

				$(this).find('div.megamenu').show();

			

				//$(this).css('min-width', 'auto');
				

/*
SET WIDTH TO SUBNAV. AND CALCULATE IF MEGAMENU WIDER THEM SUBNAV


-------------------*/				
			
			if (meagamenuInc) {
			
			var summa = 0;
			$(this).children('div.megamenu').children('div.item').each(function() {
				summa+=15;
				summa += parseInt($(this).outerWidth());
				
			}); 
			summa+=40;
			//console.log("summa "+summa);
			}
			
				if ( meagamenuInc &&  ( $(this).find('div.megamenu').find('.item').outerWidth()> wdt )  ){
					
						wdt=$(this).children('div.megamenu').children('div.item').outerWidth()+41;
						
						$(this).css('width', wdt+ 'px');
					//	console.log("1 "+wdt);
				} else {
				//wdt+=21;
				$(this).css('width', wdt+ 'px');
				
					
				}
		
				var	 wdt1 =li.outerWidth();
					
						if($(this).outerWidth()<wdt1)   {
												
								
								$(this).css('min-width', wdt1+ 'px');
								//wdt=wdt1;
							}	
			
			if ( meagamenuInc &&  ( summa > wdt )  ){
				wdt=summa;
				$(this).css('width', wdt+ 'px');
			
			}


			
/*------------------*/

					var widthSubnav = $(this).outerWidth();
					var screenwidth = $(window).width();

									 if (widthSubnav > screenwidth) {
									 		//console.log("Sovsem ne vhodit");
										 	/*hX = 400;
											hY = 550;
											hZ = 850;*/

									 }


					//console.log(wdt);

					//fix for first child li of #navigator
					var liOffset1 = 0; // offset of parent <li>
						if (subnavIndex == 0){
							liOffset1=liOffset+2;
						} else{
							liOffset1=liOffset;
						}
									
				$(this).offset({left: liOffset1});
					
							//$(this).offset({left: li.offset().left - 0});

				if( wdt - 10 > $('nav').width() - li.offset().left ) {
					
					lw =  li.offset().left + li.width();
					
					$(this).offset({left: lw - $(this).width() + 2});


					Fixleftslide = true;	
					var trueleft = 	lw - $(this).width() + 2;
					//console.log("DA. And equal to" +	$(this).offset().left);
				}	else{
					Fixleftslide = false;	
				}
				
				if ( $(this).offset().left < 0 ) {
					$(this).offset({left: li.offset().left });	
					Fixleftslide = false;	
				}

			


							/// if SUBNAV wider than screenwidth
					
					//var widthSubnav = $(this).outerWidth();
					
					var fixWiderSubnav;
					//el.offsetLeft + el.offsetWidth > p.offsetLeft + p.offsetWidth);		
				/*	console.log("Screenwidth: "+screenwidth);		
					console.log("widthSubnav: "+widthSubnav);			
					console.log("suboffsetLeft: "+suboffsetLeft);*/
				/*
				if (widthSubnav>screenwidth) {
							hX = 400;
							hY = 550;
							hZ = 850;

					} else	*/
				
				var suboffsetLeft =  $(this).offset().left;
				

				if( (widthSubnav+suboffsetLeft>screenwidth) && (widthSubnav< screenwidth) ){

					
							//console.log ("SUBNAV SLISHKOM SHIROKIY NADO DVIGAT VLEVO")
							var needleftosetsubnav = (widthSubnav+suboffsetLeft)-screenwidth+20;
							//console.log ("needleftosetsubnav: "+needleftosetsubnav);
							$(this).offset({left: li.offset().left-needleftosetsubnav });	
				}


					

			
			});
			
		

        }, function() {

    
        });





	var glt;
   // var $menu = navLI;
    navLI.mouseenter(function(){
    		 	
        var num = navLI.index(this);
        
        clearTimeout(glt);
  glt = setTimeout(function() {
  
    var $item=navLI.eq(num); 
   //	$("#navigator>li").find(".menubacklight").stop(true,true);
   navLI.stop(true,true).removeClass('hover').removeClass('overflowstyle').removeClass("menubacklight").prev('li').removeClass('clean');
   //$item.find(".menubacklight").stop(true,true);
    $item.stop(true,true).addClass('hover').addClass("menubacklight").prev('li').addClass('clean');
 	

}, 0)
 });


//----------------------------------------------------------------------
/*FUNCTION FOR ELEMENTS WITH OVERFLOW
    ----------------------------------------------------------------------*/
	navLI.mouseenter(function (){


							var element = this.querySelector('#navigator>li>a');
							var haveoverflow;
					
							if( element.offsetWidth < element.scrollWidth){
							   // your element have overflow
							//  element.style.background = "red";
							  element.className = "overflowstyle";
								haveoverflow = true;
								//console.log ("SCRIPT OVERFLOW STILL WORKING");
							}
							else{
								haveoverflow = false;
								//elementwidth = 0;
							    //your element don't have overflow
							
							}

			
				
				var havesubnav = false;
				//check if have subnav
				if($(this).hasClass('no-subnav')){
					
					havesubnav = false;
				} else {
					havesubnav = true;
				}
					
				if	(havesubnav && haveoverflow && Fixleftslide) 	{
						
							var width = this.querySelector('.subnav').offsetWidth;
							var offetleft = this.querySelector('.subnav').offsetLeft;
							var needtooffset = (parseInt(width)+parseInt(offetleft)) ;
							
							function setWidth(){
								var elementwidth = element.offsetWidth;
						//		console.log( "Elementwidth"+elementwidth);
								element.style.left  = needtooffset-elementwidth+1+'px';
							}
							setWidth();
							//console.log("Its still working");
							setWidth();

				}

				///Check after resize when set position absolute and change offset
				var	 wdt1 =element.offsetWidth;
				if (havesubnav){
						if(this.querySelector('.subnav').offsetWidth<wdt1)   {
												
								
								$(this).find('div.subnav').css('min-width', wdt1+ 'px');
							}	
				}		
		});
navLI.mouseleave(function (){
						var element = this.querySelector('#navigator>li>a');
							if( element.className == "overflowstyle"){
							   // your element have overflow
							
							 	element.style.left = 0;
							  element.className = element.className.replace('overflowstyle','');

							}
						Fixleftslide = false;	
						

						});
//----------------------------------------------------------------------






if (!OldBrowsver) {

navLI.mouseleave(function() {
	




clearTimeout(glt);




var linkcolor = navigation.find('li>a').not($(this).find('a')).first().css('Color');

	navigation.children("li.menubacklight").find('a').first().animate({

		'backgroundColor':'rgba(255, 255, 255, 0)',
		'color':"rgb("+linkcolor+")",
		'boxShadow': '-4px 0px 3px -3px rgba(0, 0, 0, 0), 4px 0px 3px -3px rgba(0, 0, 0, 0)'
		
	}, YSMenu.Speed.animate,  function(){ 
  		
   
	  	navLI.stop(true,true).removeClass('overflowstyle').removeClass('hover').removeClass('menubacklight').prev('li').removeClass('clean');
		navLI.removeClass("menubacklight");
		$(this).removeAttr('style');
	
	   
   });



		
  $(this).find('div.subnav').fadeOut(YSMenu.Speed.fadeOut, function(){ 
  	 clearTimeout(glt);
   
 	//glt = setTimeout(function() {
   			
	     //$("#navigator>li").stop(true,true).removeClass('hover').removeClass('menubacklight').prev('li').removeClass('clean');
	  // 	$("#navigator:first>li> a").removeClass('menubacklight');

   
   
	//},500)

	});


  	
		
}); 
}   else {
		navLI.hover(function(){
			$(this).addClass('ys-menu-bg-ie8');
		}, function(){
			$(this).removeClass('ys-menu-bg-ie8');
		});
		navigation.mouseleave(function() {
				
					navLI.stop(true,true).removeClass('overflowstyle').removeClass('menubacklight').removeClass('hover').prev('li').removeClass('clean');
					navLI.removeClass("menubacklight");
					navLI.removeClass('ys-menu-bg-ie8');
					

		});	
		navLI.find("div.subnav").mouseleave(function() {
				navLI.stop(true,true).removeClass('overflowstyle').removeClass('menubacklight').removeClass('hover').prev('li').removeClass('clean');
				navLI.removeClass("menubacklight");
		});
		
}


//!mouseleave


	navLI.on("mouseenter","div.subnav", function() {
		var $mask = $(this).parents('.navmenu').children('.ye_horiz_mask');
		if ($mask.length) {

			$mask.fadeIn('300');
		}
	});
		
	navLI.mouseleave(function() {

		if ($('.ye_horiz_mask').length) {

				$('div.ye_horiz_mask').stop(true, true).hide();
		}

	});

navLI.find(".subnav").mouseleave(function() {

		if ($('.ye_horiz_mask').length) {

				$('div.ye_horiz_mask').stop(true, true).hide();
		}

	});

	if ($('.ye_horiz_mask').length) {

		$('div.ye_horiz_mask').hover(function() {
			$('div.ye_horiz_mask').hide();
		});
	}


//});
		



    /*NAV COLUMN
    ----------------------------------------------------------------------*/
	    navigation.find('div.subnav ul:last').addClass('last');
    
		/* $("nav a.item-selected").attr("href", "javascript:void(0);");
        $("nav a.item-selected").css("font-weight", "bold");
        $("nav a.item-selected").css("color", "#333");
        $("nav a.item-selected").css("border-bottom", "none");
        $("nav a.item-selected").css("cursor", "default");
        $("nav li.active").prev().attr("class", "clean");
        $("nav li.active a").hover(function(){}, function(){ $("nav li.active").prev().style("background", "none", "important");}); */




});



document.createElement("nav");




