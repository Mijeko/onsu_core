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
        }
    );

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
	<li><a class="yenisite-zoom" rel="fancy-tour" href="<?=CFile::GetPath($pathbb)?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>"><img src="<?=CFile::GetPath($path)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a></li>
<?
		endforeach;
	endif;		
?>
</ul>


