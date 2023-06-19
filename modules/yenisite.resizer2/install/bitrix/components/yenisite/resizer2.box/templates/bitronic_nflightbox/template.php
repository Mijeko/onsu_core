<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (!isset($arParams['SHOW_DELAY_DETAIL'])) $arParams['SHOW_DELAY_DETAIL'] = '300';
if (!isset($arParams['HIDE_DELAY_DETAIL'])) $arParams['HIDE_DELAY_DETAIL'] = '600';
if (!isset($arParams['OVERLAY_OPACITY'])) $arParams['OVERLAY_OPACITY'] = '0.5';
if (!isset($arParams['RESIZE_SPEED'])) $arParams['RESIZE_SPEED'] = '500';
if (!isset($arParams['BORDER_SIZE'])) $arParams['BORDER_SIZE'] = '10';
if (!isset($arParams['SLIDE_SHOW_TIMER'])) $arParams['SLIDE_SHOW_TIMER'] = '5000';
if (empty($arParams['FIXED_NAVIGATION'])) $arParams['FIXED_NAVIGATION'] = 'true';
?>

<script type="text/javascript">
    $(document).ready(
        function(){
			var settings = { 
				overlayBgColor: '#<?=$arParams['OVERLAY_BG_COLOR']?>',
				overlayOpacity: <?=$arParams["OVERLAY_OPACITY"];?>,
				fixedNavigation:<?=$arParams["FIXED_NAVIGATION"];?>,
				containerResizeSpeed: <?=$arParams["RESIZE_SPEED"];?>, 
				containerBorderSize: <?=$arParams["BORDER_SIZE"];?>,
				slideShowTimer: <?=$arParams["SLIDE_SHOW_TIMER"];?>,
				
			}; 
		
			$('.yenisite-zoom').lightBox(settings);
			
			
            $(".yenisite-icons").click(
                function(){
                    path = $(this).attr("rel");
					title = $(this).attr("title");
                    pathb = $(this).parent().attr("rel");
					$("span.yenisite-desc").text(title);
					$(".yenisite-detail").attr("alt", title);
					$(".yenisite-detail").attr("title", title);
                    $(".yenisite-buff").attr("rel", path);
                    $(".yenisite-buff").attr("src", path);
                    $(".yenisite-zoom").attr("href", pathb);
					$(".yenisite-zoom").attr("title", title);
					
					
                }
            );
        }
		
			
	);

    function loader()
    {
        if($(".yenisite-detail").attr("src") != $(".yenisite-buff").attr("src"))
        {          
            $(".yenisite-detail").animate(
                {opacity: "hide"},
                <?=$arParams["HIDE_DELAY_DETAIL"];?>,
                function(){
                    $(".yenisite-detail").attr("src", $(".yenisite-buff").attr("src"));
                    $(".yenisite-detail").animate(
                        {opacity: "show"},
                    <?=$arParams["SHOW_DELAY_DETAIL"];?>);
                }
            );
            
        }

    }
</script>

<div class='yenisite-photos'>
	
<?
if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 1;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;
CModule::IncludeModule('yenisite.resizer2');
if($arResult['PATH'][0]):
	$path = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_DETAIL']);
	$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_BIG_DETAIL']);
	
?>

<div class="yenisite-bigphoto">
	<a class="yenisite-zoom" href="<?=$pathb?>" title="<?=$arResult["DESCRIPTION"][0]?>"><img class="yenisite-detail" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][0]?>" alt="<?=$arResult["DESCRIPTION"][0]?>" /></a>
	<img class="yenisite-buff" src="<?=$path?>" style="display: none;" onload="loader();" rel="<?=$path?>" />
	
</div>
<?endif?>

	<ul id="yenisite-gallery">
	<?
	$i=0;
	if(count($arResult["PATH"]) >1):
		foreach($arResult["PATH"] as $value):
			$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
			$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;							
        ?>
		<li><a href="javascript:void(0);" rel="<?=$pathbb?>"><img class="yenisite-icons" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" rel="<?=$pathb?>" /></a>
		<?endforeach?>
	<?endif?>
	</ul>
</div>
