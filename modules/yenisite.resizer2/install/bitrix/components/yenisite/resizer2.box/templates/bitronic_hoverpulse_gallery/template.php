<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (!isset($arParams['SHOW_DELAY_DETAIL'])) $arParams['SHOW_DELAY_DETAIL'] = '300';
if (!isset($arParams['HIDE_DELAY_DETAIL'])) $arParams['HIDE_DELAY_DETAIL'] = '600';
if (!isset($arParams['SIZE'])) $arParams['SIZE'] = '20';
if (!isset($arParams['SPEED'])) $arParams['SPEED'] = '200';
?>

<script type="text/javascript">
    
$(document).ready(
        function(){
			jQuery('#gallery img').hoverpulse({
			size:<?=$arParams["SIZE"];?>,
			speed:<?=$arParams["SPEED"];?>
			});
			
					var hover = $("#gallery");
					var currentHeight = hover.css('height');
					var numHeight = parseFloat(currentHeight,10);
					var size = <?=$arParams["SIZE"];?>;
					//$hover.css('width','500px');
					var summa = numHeight+size;
					hover.css({height: summa});
					//hover.css('padding-left',size);
					//console.log(currentHeight,numHeight,size,summa);
					/*var hover = $("#gallery li .yenisite-zoom  img");
					hover.css({height: 250,width: 250});
					console.log(hover.css('height'),hover.css('width'));
					var hover = $("#gallery li");
					hover.css({height: 250,width: 250});*/
			
        }
    );
	/*
	$(function() {
			$("#gallery .yenisite-zoom img").mouseenter(function options(){
							var hover = $("#gallery");
							var size = <?=$arParams["SIZE"];?>;	
							hover.css('padding-left',size);
						});		
			$("#gallery .yenisite-zoom img").mouseleave(function options(){
							var hover = $("#gallery");
							var size = <?=$arParams["SIZE"];?>;	
							hover.css('padding-left',0);
						});				
	});*/
	/*
	$(function() {
			  $("#gallery .yenisite-zoom img").mouseleave(function options(){
							setTimeout(function(){
	
								var hover = $("#gallery li .yenisite-zoom  img");
								hover.css({height: 250,width: 250});
								console.log(hover.css('height'),hover.css('width'));
							},220);	
						});			
	});
	*/
    function loader()
    {
        if($(".yedetail").attr("src") != $(".yebuff").attr("src"))
        {          
            $(".yedetail").animate(
                {opacity: "hide"},
                <?=$arParams["HIDE_DELAY_DETAIL"];?>,
                function(){
                    $(".yedetail").attr("src", $(".yebuff").attr("src"));
                    $(".yedetail").animate(
                        {opacity: "show"},
                    <?=$arParams["SHOW_DELAY_DETAIL"];?>);
                }
            );
            
        }

    }
</script>


<ul id="gallery">
<?
if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 1;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;
CModule::IncludeModule('yenisite.resizer2');
	$i=0;
	if(count($arResult["PATH"]) >0):
		foreach($arResult["PATH"] as $value):
			$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
			$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;							
?>
	<li><a class="yenisite-zoom" rel="tour" href="javascript:void(0);<?/*=CFile::GetPath($pathbb)*/?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>"><img src="<?=CFile::GetPath($pathb)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a></li>
<?
		endforeach;
	endif;		
?>
</ul>
